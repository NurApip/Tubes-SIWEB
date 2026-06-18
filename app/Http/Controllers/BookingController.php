<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 0) Ambil input dasar
        $request->validate([
            'lapangan_id' => 'required',
            'tgl_main' => 'required|date',
            'jam_mulai' => 'required',
            'durasi' => 'required|integer|min:1|max:4',
            'nama_penyewa' => 'required|string|max:100',
            'nomor_wa' => ['required', 'string', 'max:25', 'regex:/^[0-9+\s]+$/'],
        ], [
            'nomor_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, dan simbol +.',
        ]);

        $lapangan = Lapangan::findOrFail($request->lapangan_id);

        // Arena non-aktif: blokir booking
        if ((bool)($lapangan->is_active ?? true) === false) {
            return back()->with('error', 'Arena sedang tidak aktif (maintenance).');
        }


        // 1) Mapping jam -> slot global operasional
        $jam = (string)$request->jam_mulai;
        $slot = match ($jam) {
            '08:00', '09:00' => 'Pagi',
            '16:00' => 'Siang',
            '19:00', '20:00' => 'Malam',
            default => null,
        };

        if (!$slot) {
            return back()->with('error', 'Jam kick-off tidak valid.');
        }

        // 2) LOGIKA-1: Tolak jika slot global is_full=true
        $operasional = \App\Models\LapanganOperasionalToday::where('lapangan_id', $lapangan->lapangan_id)
            ->where('slot', $slot)
            ->first();

        if ($operasional && $operasional->is_full) {
            return back()->with('error', 'Maaf, slot waktu pada jam tersebut sudah dipesan oleh tim lain (Penuh global). Silakan pilih jam lain.');
        }

        // 3) LOGIKA-2: Tolak jika bentrok di bookings
        $durasi = (int) $request->durasi;

        $jamMulaiBaru = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesaiBaru = $jamMulaiBaru->copy()->addHours($durasi);

        $bookingsAktif = Booking::where('lapangan_id', $lapangan->lapangan_id)
            ->where('tgl_main', $request->tgl_main)
            ->whereIn('status', ['Pending', 'Success'])
            ->get();

        foreach ($bookingsAktif as $bookingLama) {

            $jamMulaiLama = Carbon::createFromFormat(
                'H:i',
                substr($bookingLama->jam_mulai, 0, 5)
            );

            $durasiLama = (int) ($bookingLama->durasi ?? 1);

            $jamSelesaiLama = $jamMulaiLama->copy()->addHours($durasiLama);

            $bentrok =
                $jamMulaiLama->lt($jamSelesaiBaru) &&
                $jamSelesaiLama->gt($jamMulaiBaru);

            if ($bentrok) {
                return back()->with(
                    'error',
                    'Maaf, jadwal tersebut bentrok dengan booking lain.'
                );
            }
        }

        // 4) Simpan data ke tabel bookings
        $user = User::findOrFail(Auth::id());
        $subtotal = $lapangan->harga_per_jam * $durasi;
        $diskonMember = $user && $user->isActiveMember() ? ($subtotal * 0.10) : 0;
        $totalHarga = max($subtotal - $diskonMember, 0);

        Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->lapangan_id,
            'nama_gor' => $lapangan->nama_lapangan,
            'nama_penyewa' => $request->nama_penyewa,
            'nomor_wa' => $request->nomor_wa,
            'tgl_main' => $request->tgl_main,
            'jam_mulai' => $request->jam_mulai,
            'durasi' => $durasi,
            'durasi_bermain' => $durasi,
            'total_harga' => $totalHarga,
            'status' => 'Pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking berhasil! Silahkan cek riwayat.');
    }

    public function checkout(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        if ((bool)($lapangan->is_active ?? true) === false) {
            return redirect()->route('dashboard')->with('error', 'Arena sedang tidak aktif dan belum dapat dipesan.');
        }

        // Ambil data tanggal, jam, dan data penyewa dari form sebelumnya (GET)
        $tanggal = $request->query('tgl_main');
        $jam = $request->query('jam_mulai');
        $nama_penyewa = $request->query('nama_penyewa');
        $nomor_wa = $request->query('nomor_wa');

        // Lempar semua data ke file booking/pemesanan.blade.php
        return view('booking.pemesanan', compact('lapangan', 'tanggal', 'jam', 'nama_penyewa', 'nomor_wa'));
    }
    public function index()
    {
        // Mengambil ID user yang sedang login menggunakan facade Auth
        $userId = Auth::id();

        $bookings = Booking::where('user_id', $userId)
            ->with('review')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.index', compact('bookings'));
    }
    public function joinMember(Request $request)
    {
        $request->validate([
            'bukti_membership' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::findOrFail(Auth::id());

        if ($user->isActiveMember()) {
            return back()->with('error', 'Akun Anda sudah berstatus member aktif.');
        }

        if ($user->isPendingMember()) {
            return back()->with('error', 'Pengajuan membership Anda masih menunggu verifikasi admin.');
        }

        if ($request->hasFile('bukti_membership')) {
            $path = $request->file('bukti_membership')->store('membership_bukti', 'public');

            if ($user->membership_bukti) {
                Storage::disk('public')->delete($user->membership_bukti);
            }

            $user->update([
                'is_member' => 2,
                'membership_bukti' => $path,
                'membership_requested_at' => now(),
                'membership_started_at' => null,
                'membership_expires_at' => null,
            ]);

            return back()->with('success', 'Bukti pembayaran membership berhasil dikirim! Menunggu verifikasi admin.');
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran membership.');
    }
    // app/Http/Controllers/BookingController.php

    // Pastikan ada ini di bagian paling atas file (di bawah namespace)
    // use Illuminate\Support\Facades\Storage; 

    public function uploadBukti(Request $request, $id)
    {
        // 1. Validasi file: Harus gambar (jpg, png, jpeg) & maksimal 2MB
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // 2. Cari data bookingnya
        $booking = Booking::findOrFail($id);

        // 3. Proses upload file
        if ($request->hasFile('bukti_bayar')) {
            // Simpan file ke folder: storage/app/public/bukti_pembayaran
            $path = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');

            // Simpan nama path-nya ke database
            $booking->update([
                'bukti_bayar' => $path
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Tunggu verifikasi admin.');
    }
    public function kwitansi($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'Success') // Hanya bisa cetak kalau sudah sukses
            ->firstOrFail();

        return view('booking.kwitansi', compact('booking'));
    }

    /**
     * Send WhatsApp notification synchronously via WA gateway
     */
    /**
     * Send WhatsApp notification synchronously via WA gateway
     */
    public static function sendWaNotification(Booking $booking)
    {
        try {
            $url = "https://api.fonnte.com/send";
            $token = "uyR8eeAst6ZuPiKR8uHj";

            // AMBIL LANGSUNG DARI KOLOM nomor_wa DI TABEL BOOKINGS
            $target = $booking->nomor_wa ?? null;

            if (!$target) {
                Log::warning('Kolom nomor_wa di tabel bookings kosong, gagal kirim WA', [
                    'booking_id' => $booking->id,
                ]);
                return false;
            }

            // Bersihkan karakter selain angka (menghapus spasi, strip, atau tanda +)
            $target = preg_replace('/[^0-9]/', '', $target);

            // Pastikan format nomor diawali kode negara (62) jika user menginput berawalan 08
            if (substr($target, 0, 1) === '0') {
                $target = '62' . substr($target, 1);
            }

            $message = "*FUTSALHUB NOTIFICATION* ⚽\n\n" .
                "------------------------------------------\n\n" .
                "Halo *" . ($booking->nama_penyewa ?? 'Pelanggan') . "*, Pembayaran Anda telah TERVERIFIKASI oleh Admin!\n\n" .
                "Detail Jadwal:\n" .
                "- Lapangan: " . ($booking->nama_gor ?? '-') . "\n" .
                "- Tanggal: " . ($booking->tgl_main ?? '-') . "\n" .
                "- Jam: " . ($booking->jam_mulai ?? '-') . "\n" .
                "- Durasi Bermain: " . ($booking->durasi ?? '-') . " jam\n" .
                "- Kode Tiket: " . ($booking->kode_tiket ?? '-') . "\n\n" .
                "Silakan cek dashboard FutsalHub Anda untuk melihat detail jadwal dan mengunduh Kwitansi Resmi Anda. Terima kasih!";

            // Menggunakan cURL native PHP agar eksekusi instan tanpa membebani memory cache Windows
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: $token"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                Log::error('cURL Error pada gateway WA:', [
                    'error' => $err,
                    'booking_id' => $booking->id
                ]);
                return false;
            }

            // Mencatat log respon dari Fonnte agar terlihat status asli pengirimannya
            Log::info('Respon Fonnte Berhasil Diterima:', [
                'response' => $response,
                'booking_id' => $booking->id
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Sistem gagal memproses fungsi WA', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);
            return false;
        }
    }
}
