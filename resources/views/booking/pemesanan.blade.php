@extends('layouts.app')

@section('title', 'Konfirmasi Pemesanan')

@section('content')
@php
$isActiveMember = Auth::check() && Auth::user()->isActiveMember();
$diskonPersen = $isActiveMember ? 10 : 0;
$diskonMember = $isActiveMember ? ($lapangan->harga_per_jam * 0.10) : 0;
@endphp

<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-black uppercase mb-8 italic tracking-tighter">
        Konfirmasi <span class="text-blue-600">Pemesanan</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-6">

            {{-- 1. FOTO ARENA (Dinamis dari Database) --}}
            <div class="w-full h-80 bg-gray-100 rounded-[2.5rem] overflow-hidden shadow-xl border border-gray-100 relative group">
                @if($lapangan->foto)
                {{-- Mengambil foto dari storage sesuai isi kolom 'foto' di DB --}}
                <img src="{{ asset('storage/' . $lapangan->foto) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                @else
                {{-- Fallback: Jika kolom foto masih kosong, muncul icon bola agar sinkron dengan dashboard --}}
                <div class="flex flex-col items-center justify-center h-full bg-gray-50 text-gray-200">
                    <i class="fas fa-futbol text-9xl"></i>
                    <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Preview Arena Belum Diupload</p>
                </div>
                @endif

                <div class="absolute bottom-6 left-6">
                    <span class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-xl text-[10px] font-black text-blue-600 uppercase shadow-lg border border-white">
                        <i class="fas fa-check-circle mr-2"></i> Verified Arena
                    </span>
                </div>
            </div>

            {{-- 2. DETAIL ARENA (Header Nama GOR Diperbesar) --}}
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex flex-col mb-6">
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2 italic">Arena Yang Anda Pilih:</p>
                    <h3 class="text-5xl font-black text-gray-800 uppercase italic tracking-tighter leading-none">
                        {{ $lapangan->nama_lapangan }}
                    </h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-400 uppercase font-black mb-0.5">Tanggal Main</p>
                            <p class="font-black text-gray-700 text-sm tracking-tight">{{ $tanggal }}</p>
                        </div>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="far fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-400 uppercase font-black mb-0.5">Jam Kick-Off</p>
                            <p class="font-black text-gray-700 text-sm tracking-tight">{{ $jam }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- 4. SIDEBAR PEMBAYARAN (Sticky) --}}
        <div class="bg-white p-8 rounded-[2.5rem] border-2 border-blue-600 shadow-2xl h-fit sticky top-24">
            <p class="text-[10px] font-black text-gray-400 uppercase mb-6 tracking-widest text-center italic">Checkout Summary</p>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-[10px] font-black uppercase">Tarif Lapangan</span>
                    <span class="font-black text-gray-800">Rp {{ number_format($lapangan->harga_per_jam) }}</span>
                </div>

                @auth
                @if($isActiveMember)
                <div class="flex justify-between items-center text-green-600 bg-green-50 p-4 rounded-2xl border border-green-100">
                    <span class="text-[10px] font-black uppercase tracking-tighter"><i class="fas fa-crown mr-1"></i> Diskon Member</span>
                    <span class="font-black">Diskon 10%</span>
                </div>
                @endif
                @endauth
            </div>

            <div class="mb-6">
                <label class="text-[9px] font-black text-gray-400 uppercase block mb-3 tracking-widest">
                    Durasi Bermain
                </label>

                <select
                    name="durasi"
                    id="durasi"
                    class="w-full border border-gray-200 p-4 rounded-2xl font-black text-gray-700 focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="1">1 Jam</option>
                    <option value="2">2 Jam</option>
                    <option value="3">3 Jam</option>
                    <option value="4">4 Jam</option>
                </select>
            </div>

            <div class="border-t border-dashed border-gray-200 my-6"></div>

            <div class="text-center mb-8">
                <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Total Yang Harus Dibayar</p>
                <span
                    id="total-harga"
                    class="font-black text-blue-600 text-4xl tracking-tighter block">
                    Rp {{ number_format($lapangan->harga_per_jam - $diskonMember) }}
                </span>
            </div>

            <div class="mb-8">
                <label class="text-[9px] font-black text-gray-400 uppercase block mb-3 tracking-widest text-center">Metode Transfer</label>
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl text-center">
                    <p class="text-[10px] font-black text-blue-800 uppercase italic">Bank Mandiri / FutsalHub</p>
                    <p class="text-lg font-black text-blue-900 tracking-widest mt-1">1234-5678-90</p>
                </div>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-5 bg-red-50 border border-red-100 rounded-3xl text-sm text-red-700">
                <p class="font-black uppercase tracking-widest mb-3">Perbaiki data berikut:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                {{-- Pastikan ID lapangan menggunakan kolom primary key 'lapangan_id' --}}
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->lapangan_id }}">
                <input type="hidden" name="tgl_main" value="{{ $tanggal }}">
                <input type="hidden" name="jam_mulai" value="{{ $jam }}">
                <input type="hidden" name="nama_penyewa" value="{{ old('nama_penyewa', $nama_penyewa) }}">
                <input type="hidden" name="nomor_wa" value="{{ old('nomor_wa', $nomor_wa) }}">
                <input type="hidden" name="durasi" id="durasi-hidden" value="1">

                <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100 mb-6">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Data Pemesan</p>

                    <div class="grid gap-4">
                        <div>
                            <p class="text-[9px] uppercase text-gray-500 tracking-widest mb-1">Nama Penyewa / Tim</p>
                            <p class="text-sm font-black text-gray-800">{{ old('nama_penyewa', $nama_penyewa ?? '-') }}</p>
                        </div>

                        <div>
                            <p class="text-[9px] uppercase text-gray-500 tracking-widest mb-1">Nomor WhatsApp</p>
                            <p class="text-sm font-black text-gray-800">{{ old('nomor_wa', $nomor_wa ?? '-') }}</p>
                        </div>

                        <div>
                            <p class="text-[9px] uppercase text-gray-500 tracking-widest mb-1">Estimasi Selesai</p>
                            <p id="jam-selesai" class="text-sm font-black text-green-600">-</p>
                        </div>
                    </div>
                </div>

                @php
                use App\Models\LapanganOperasionalToday;

                $isActive = (bool)($lapangan->is_active ?? true);

                $jamStr = (string)($jam ?? '');
                $slot = match ($jamStr) {
                '08:00', '09:00' => 'Pagi',
                '16:00' => 'Siang',
                '19:00', '20:00' => 'Malam',
                default => null,
                };

                $operasional = $slot
                ? LapanganOperasionalToday::where('lapangan_id', $lapangan->lapangan_id)
                ->where('slot', $slot)
                ->first()
                : null;

                $isSlotFull = $operasional ? (bool)($operasional->is_full ?? false) : false;
                @endphp

                @php $disableBecauseFull = $slot ? $isSlotFull : false; @endphp

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-black py-6 rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 active:scale-95 transition-all duration-300 disabled:opacity-60 disabled:hover:bg-blue-600"
                    @if(!$isActive || $disableBecauseFull) disabled @endif>
                    @if(!$isActive)
                    Arena Non-Aktif
                    @elseif($disableBecauseFull)
                    Slot Penuh
                    @else
                    Konfirmasi & Bayar
                    @endif
                </button>

            </form>

            <p class="text-[8px] text-center text-gray-400 font-bold uppercase mt-6 tracking-widest">
                <i class="fas fa-lock mr-1 text-green-500"></i> Pembayaran Terverifikasi Manual
            </p>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const durasiSelect = document.getElementById('durasi');
        const durasiHidden = document.getElementById('durasi-hidden');

        const totalHarga = document.getElementById('total-harga');
        const jamSelesai = document.getElementById('jam-selesai');

        const hargaPerJam = {{ $lapangan->harga_per_jam }};
        const diskonPersen = {{ $diskonPersen }};

        const jamMulai = "{{ $jam }}";

        function formatRupiah(nominal) {
            return 'Rp ' + nominal.toLocaleString('id-ID');
        }

        function updateBooking() {
            const durasi = parseInt(durasiSelect.value);

            durasiHidden.value = durasi;

            const subtotal = hargaPerJam * durasi;
            const diskonMember = subtotal * (diskonPersen / 100);
            const total = subtotal - diskonMember;

            totalHarga.innerText =
                formatRupiah(total);

            const parts = jamMulai.split(':');

            let jam =
                parseInt(parts[0]);

            let menit =
                parseInt(parts[1]);

            jam += durasi;

            jamSelesai.innerText =
                String(jam).padStart(2, '0') +
                ':' +
                String(menit).padStart(2, '0') +
                ' WIB';
        }

        durasiSelect.addEventListener('change', updateBooking);

        updateBooking();
    });
</script>
@endsection
