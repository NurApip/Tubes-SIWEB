<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalPendapatan = Booking::where('status', 'Success')->sum('total_harga') ?? 0;
        $totalBooking = Booking::count();
        $bookingPending = Booking::where('status', 'Pending')->count();
        $totalMember = User::where('role', '!=', 1)
            ->where('is_member', 1)
            ->where('membership_expires_at', '>', now())
            ->count();

        $bookingStatus = [
            'Success' => Booking::where('status', 'Success')->count(),
            'Pending' => $bookingPending,
            'Cancelled' => Booking::where('status', 'Cancelled')->count(),
        ];

        $bookingTerbaru = Booking::with(['user', 'lapangan'])
            ->latest()
            ->limit(3)
            ->get();

        $aktivitasTerbaru = Booking::with(['user', 'lapangan'])
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(function (Booking $booking) {
                $userName = $booking->user->name ?? $booking->nama_penyewa ?? 'Pengguna';
                $gorName = $booking->lapangan->nama_lapangan ?? $booking->nama_gor;

                if ($booking->status === 'Success') {
                    return [
                        'date' => $booking->updated_at,
                        'icon' => 'fa-check',
                        'color' => 'green',
                        'text' => "Admin mengubah booking {$userName} menjadi Success",
                    ];
                }

                if ($booking->status === 'Cancelled') {
                    return [
                        'date' => $booking->updated_at,
                        'icon' => 'fa-times',
                        'color' => 'red',
                        'text' => "Booking {$userName} untuk {$gorName} dibatalkan",
                    ];
                }

                if ($booking->bukti_bayar) {
                    return [
                        'date' => $booking->updated_at,
                        'icon' => 'fa-upload',
                        'color' => 'blue',
                        'text' => "{$userName} mengunggah bukti pembayaran",
                    ];
                }

                return [
                    'date' => $booking->created_at,
                    'icon' => 'fa-calendar-plus',
                    'color' => 'yellow',
                    'text' => "{$userName} melakukan booking {$gorName}",
                ];
            });

        return view('admin.dashboard', compact(
            'totalPendapatan',
            'totalBooking',
            'bookingPending',
            'totalMember',
            'bookingStatus',
            'bookingTerbaru',
            'aktivitasTerbaru'
        ));
    }

    public function members()
    {
        $totalMember = User::where('role', '!=', 1)->count();
        $totalSesi = Booking::count();
        $totalUang = Booking::sum('total_harga') ?? 0;
        $users = User::where('role', '!=', 1)->get();

        return view('admin.members', compact('totalMember', 'totalSesi', 'totalUang', 'users'));
    }

    public function pendapatan()
    {
        $bookings = Booking::with(['user', 'lapangan'])
            ->where('status', 'Success')
            ->latest('updated_at')
            ->get()
            ->each(function (Booking $booking) {
                $start = Carbon::createFromFormat('H:i', substr($booking->jam_mulai, 0, 5));
                $duration = (int) ($booking->durasi_bermain ?? $booking->durasi ?? 1);
                $booking->jam_selesai_label = $start->copy()->addHours($duration)->format('H:i');
            });

        $totalPendapatan = $bookings->sum('total_harga');
        $pendapatanPerLapangan = $bookings
            ->groupBy(fn (Booking $booking) => $booking->lapangan->nama_lapangan ?? $booking->nama_gor)
            ->map(fn ($group) => $group->sum('total_harga'))
            ->sortDesc();
        $pendapatanTertinggi = max((int) $pendapatanPerLapangan->max(), 1);

        return view('admin.pendapatan', compact(
            'bookings',
            'totalPendapatan',
            'pendapatanPerLapangan',
            'pendapatanTertinggi'
        ));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'lapangan'])->orderBy('created_at', 'desc')->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function bookingDetail($id)
    {
        $booking = Booking::with(['user', 'lapangan'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function bookingUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Success,Cancelled',
        ]);

        $booking = DB::transaction(function () use ($id, $validated) {
            $booking = Booking::lockForUpdate()->findOrFail($id);

            if ($booking->status !== 'Pending') {
                return null;
            }

            if ($validated['status'] === 'Success' && empty($booking->kode_tiket)) {
                do {
                    $kode = 'FH-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
                } while (Booking::where('kode_tiket', $kode)->exists());

                $booking->kode_tiket = $kode;
            }

            $booking->status = $validated['status'];
            $booking->save();

            return $booking;
        });

        if ($booking === null) {
            return back()->with('error', 'Status booking sudah final dan tidak dapat diubah lagi.');
        }

        if ($booking->status === 'Success') {
            try {
                \App\Http\Controllers\BookingController::sendWaNotification($booking);
            } catch (\Exception $e) {
                Log::error('Failed to send WA notification', ['error' => $e->getMessage(), 'booking_id' => $booking->id]);
            }
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function bookingDestroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings')->with('success', 'Booking berhasil dihapus.');
    }

    public function memberUpdate(Request $request, $id)
    {
        $request->validate([
            'is_member' => 'required|in:0,1',
        ]);

        $user = User::where('role', '!=', 1)->findOrFail($id);

        if (!$user->isPendingMember()) {
            return back()->with('error', 'Hanya pengajuan pending yang dapat diproses.');
        }

        $nextStatus = (int) $request->is_member;

        $user->is_member = $nextStatus;

        if ($nextStatus === 1) {
            $startedAt = now();
            $user->membership_started_at = $startedAt;
            $user->membership_expires_at = $startedAt->copy()->addDays(30);
        }

        if ($nextStatus === 0) {
            if ($user->membership_bukti) {
                Storage::disk('public')->delete($user->membership_bukti);
            }

            $user->membership_bukti = null;
            $user->membership_requested_at = null;
            $user->membership_started_at = null;
            $user->membership_expires_at = null;
        }

        $user->save();

        return back()->with('success', 'Status membership berhasil diperbarui.');
    }
}
