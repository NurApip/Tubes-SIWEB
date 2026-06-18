@extends('layouts.app')

@section('title', 'Cara Booking')

@section('content')
@php
    $steps = [
        [
            'icon' => 'fa-user-plus',
            'title' => 'Masuk atau Daftar',
            'description' => 'Login menggunakan akun FutsalHub. Jika belum punya akun, lakukan pendaftaran terlebih dahulu.',
        ],
        [
            'icon' => 'fa-search-location',
            'title' => 'Pilih GOR',
            'description' => 'Buka menu Cari GOR, gunakan filter yang tersedia, lalu pilih lapangan yang sesuai kebutuhan tim.',
        ],
        [
            'icon' => 'fa-calendar-check',
            'title' => 'Pilih Jadwal',
            'description' => 'Tentukan tanggal, jam kick-off, nama penyewa atau tim, dan nomor WhatsApp aktif.',
        ],
        [
            'icon' => 'fa-clipboard-check',
            'title' => 'Konfirmasi Pesanan',
            'description' => 'Periksa kembali detail lapangan, pilih durasi bermain, lalu tekan tombol Konfirmasi & Bayar.',
        ],
        [
            'icon' => 'fa-file-upload',
            'title' => 'Upload Bukti Bayar',
            'description' => 'Transfer sesuai total pembayaran, kemudian upload bukti melalui menu Pesanan Saya.',
        ],
        [
            'icon' => 'fa-ticket-alt',
            'title' => 'Tunggu Verifikasi',
            'description' => 'Admin akan memverifikasi pembayaran. Setelah disetujui, kode tiket dan kwitansi tersedia di riwayat pesanan.',
        ],
    ];
@endphp

<div class="w-full py-8">
    <section class="relative overflow-hidden rounded-[3rem] bg-blue-900 px-8 py-14 text-white shadow-2xl shadow-blue-200 md:px-14">
        <div class="relative z-10 max-w-3xl">
            <p class="mb-4 text-[10px] font-black uppercase tracking-[0.4em] text-blue-300">Panduan FutsalHub</p>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter md:text-6xl">
                Cara Booking Lapangan
            </h1>
            <p class="mt-6 max-w-2xl text-sm font-bold leading-relaxed text-blue-100">
                Booking lapangan futsal cukup dalam beberapa langkah. Pilih jadwal, selesaikan pembayaran, lalu tunggu konfirmasi admin.
            </p>
        </div>

        <i class="fas fa-futbol absolute -bottom-16 -right-10 text-[18rem] text-white opacity-5"></i>
    </section>

    <section class="mt-12">
        <div class="mb-8 text-center">
            <p class="text-[10px] font-black uppercase tracking-[0.35em] text-blue-600">Langkah Pemesanan</p>
            <h2 class="mt-3 text-3xl font-black uppercase italic tracking-tight text-gray-800">Ikuti 6 Langkah Mudah</h2>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($steps as $index => $step)
                <article class="group rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-700 transition group-hover:bg-blue-700 group-hover:text-white">
                            <i class="fas {{ $step['icon'] }}"></i>
                        </div>
                        <span class="text-4xl font-black italic text-gray-100">0{{ $index + 1 }}</span>
                    </div>

                    <h3 class="text-lg font-black uppercase italic text-gray-800">{{ $step['title'] }}</h3>
                    <p class="mt-3 text-xs font-medium leading-relaxed text-gray-500">{{ $step['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-[2.5rem] border border-blue-100 bg-blue-50 p-9">
            <div class="flex items-start gap-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-white">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h3 class="font-black uppercase italic text-blue-900">Perlu Diperhatikan</h3>
                    <p class="mt-3 text-xs font-bold leading-relaxed text-blue-700">
                        Gunakan nomor WhatsApp aktif dan pastikan bukti pembayaran terlihat jelas. Status booking tetap Pending sampai pembayaran diverifikasi admin.
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-[2.5rem] bg-gray-900 p-9 text-white">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Siap Bermain?</p>
            <h3 class="mt-3 text-2xl font-black uppercase italic tracking-tight">Temukan GOR Favoritmu</h3>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-xl bg-blue-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest transition hover:bg-blue-700">
                    Cari GOR
                </a>
                @guest
                    <a href="{{ route('login') }}" class="rounded-xl bg-white px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-900 transition hover:bg-gray-100">
                        Login
                    </a>
                @endguest
            </div>
        </div>
    </section>
</div>
@endsection
