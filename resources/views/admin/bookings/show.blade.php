@extends('layouts.admin')

@section('title', 'Detail Booking - Admin')

@section('active_menu','bookings')

@section('admin_content')
    <div class="mb-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Detail Booking</p>
                <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Booking #{{ $booking->id }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.bookings') }}" class="inline-flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest rounded-2xl bg-slate-800 text-white hover:bg-slate-900 transition">Kembali ke Daftar</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-5 bg-green-50 border border-green-100 rounded-3xl text-green-700 font-black uppercase tracking-wider">{{ session('success') }}</div>
    @endif

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2 bg-white rounded-[32px] p-8 border border-slate-200 shadow-sm">
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-2">Lapangan</p>
                    <p class="text-lg font-black uppercase text-slate-800">{{ $booking->nama_gor }}</p>
                    <p class="text-[11px] text-slate-500 mt-2">{{ $booking->lapangan->lokasi ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-2">Penyewa / Tim</p>
                    <p class="text-lg font-black uppercase text-slate-800">{{ $booking->nama_penyewa ?? ($booking->user->name ?? '-') }}</p>
                    <p class="text-[11px] text-slate-500 mt-2">{{ $booking->nomor_wa ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-2">Tanggal</p>
                    <p class="text-lg font-black text-slate-800">{{ $booking->tgl_main }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-2">Jam</p>
                    <p class="text-lg font-black text-slate-800">{{ $booking->jam_mulai }} WIB</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-2">Durasi Bermain</p>
                    <p class="text-lg font-black text-slate-800">{{ $booking->durasi_bermain ?? $booking->durasi }} jam</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase text-slate-400 mb-2">Kode Tiket</p>
                    <p class="text-lg font-black text-slate-800">{{ $booking->kode_tiket ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 mb-8">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-3">Keterangan Tambahan</p>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-500">Status Booking</p>
                        <p class="text-lg font-black uppercase {{ $booking->status === 'Success' ? 'text-green-700' : ($booking->status === 'Pending' ? 'text-orange-700' : 'text-red-700') }}">{{ $booking->status }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-500">Total Harga</p>
                        <p class="text-lg font-black text-slate-900">Rp {{ number_format($booking->total_harga) }}</p>
                    </div>
                    @if($booking->bukti_bayar)
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-500">Bukti Bayar</p>
                            <a href="{{ asset('storage/' . $booking->bukti_bayar) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-black text-sm hover:underline">Lihat Bukti Pembayaran</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-4">Tindakan Admin</p>
                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Ubah Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-3xl p-4 text-sm font-black uppercase text-slate-800">
                            @foreach(['Pending','Success','Cancelled'] as $status)
                                <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center w-full px-6 py-4 text-[10px] font-black uppercase rounded-3xl bg-blue-600 text-white hover:bg-blue-700 transition">Simpan Status</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-[32px] p-8 border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black uppercase text-slate-400 mb-6">Aksi Cepat</p>
            <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus booking ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-6 py-4 text-[10px] font-black uppercase rounded-3xl bg-red-600 text-white hover:bg-red-700 transition">Hapus Booking</button>
            </form>
            @if($booking->nomor_wa)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->nomor_wa) }}" target="_blank" class="mt-4 inline-flex items-center justify-center w-full px-6 py-4 text-[10px] font-black uppercase rounded-3xl bg-green-600 text-white hover:bg-green-700 transition">Hubungi Penyewa via WA</a>
            @endif
        </div>
    </div>
@endsection
