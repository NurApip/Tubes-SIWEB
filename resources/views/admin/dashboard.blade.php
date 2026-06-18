@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('active_menu','dashboard')

@section('admin_content')
<div class="mb-10">
    <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-blue-600">Dashboard Admin</p>
    <h2 class="text-4xl font-black tracking-tighter text-gray-800">
        Selamat Datang, {{ Auth::user()->name }}
    </h2>
    <p class="mt-3 text-sm font-medium text-slate-500">
        Kelola reservasi dan pantau aktivitas FutsalHub secara real-time.
    </p>
</div>

<section class="mb-10 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
    <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Pendapatan</p>
                <h3 class="mt-3 text-2xl font-black tracking-tight text-slate-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p class="mt-2 text-[9px] font-bold uppercase tracking-wider text-green-600">Booking Success</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </article>

    <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Booking</p>
                <h3 class="mt-3 text-3xl font-black tracking-tight text-slate-800">{{ $totalBooking }}</h3>
                <p class="mt-2 text-[9px] font-bold uppercase tracking-wider text-blue-600">Semua Transaksi</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </article>

    <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Booking Pending</p>
                <h3 class="mt-3 text-3xl font-black tracking-tight text-slate-800">{{ $bookingPending }}</h3>
                <p class="mt-2 text-[9px] font-bold uppercase tracking-wider text-yellow-600">Perlu Ditinjau</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-50 text-yellow-600">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </article>

    <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Member</p>
                <h3 class="mt-3 text-3xl font-black tracking-tight text-slate-800">{{ $totalMember }}</h3>
                <p class="mt-2 text-[9px] font-bold uppercase tracking-wider text-violet-600">Membership Aktif</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </article>
</section>

<section class="mb-10 grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm xl:col-span-2">
        <div class="mb-7 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-blue-600">Live Update</p>
                <h3 class="mt-1 text-lg font-black uppercase tracking-tight text-slate-800">Aktivitas Terbaru</h3>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="fas fa-bolt"></i>
            </div>
        </div>

        <div class="space-y-5">
            @forelse($aktivitasTerbaru as $activity)
                @php
                    $activityColor = match ($activity['color']) {
                        'green' => 'bg-green-50 text-green-600',
                        'red' => 'bg-red-50 text-red-600',
                        'blue' => 'bg-blue-50 text-blue-600',
                        default => 'bg-yellow-50 text-yellow-600',
                    };
                @endphp
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $activityColor }}">
                        <i class="fas {{ $activity['icon'] }} text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-black leading-relaxed text-slate-700">{{ $activity['text'] }}</p>
                        <p class="mt-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                            {{ $activity['date']->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-slate-100 p-10 text-center">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-300">Belum ada aktivitas booking</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <p class="text-[9px] font-black uppercase tracking-[0.25em] text-blue-600">Ringkasan</p>
        <h3 class="mt-1 text-lg font-black uppercase tracking-tight text-slate-800">Status Booking</h3>

        <div class="mt-8 space-y-5">
            <div class="flex items-center justify-between rounded-2xl border border-green-100 bg-green-50 p-5">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-green-500"></span>
                    <span class="text-xs font-black uppercase text-green-800">Success</span>
                </div>
                <span class="text-2xl font-black text-green-700">{{ $bookingStatus['Success'] }}</span>
            </div>

            <div class="flex items-center justify-between rounded-2xl border border-yellow-100 bg-yellow-50 p-5">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                    <span class="text-xs font-black uppercase text-yellow-800">Pending</span>
                </div>
                <span class="text-2xl font-black text-yellow-700">{{ $bookingStatus['Pending'] }}</span>
            </div>

            <div class="flex items-center justify-between rounded-2xl border border-red-100 bg-red-50 p-5">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-red-500"></span>
                    <span class="text-xs font-black uppercase text-red-800">Cancelled</span>
                </div>
                <span class="text-2xl font-black text-red-700">{{ $bookingStatus['Cancelled'] }}</span>
            </div>
        </div>
    </div>
</section>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 px-8 py-6">
        <div>
            <p class="text-[9px] font-black uppercase tracking-[0.25em] text-blue-600">Transaksi Masuk</p>
            <h3 class="mt-1 text-lg font-black uppercase tracking-tight text-slate-800">Booking Terbaru</h3>
        </div>
        <a href="{{ route('admin.bookings') }}" class="rounded-xl bg-slate-100 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-600 transition hover:bg-blue-600 hover:text-white">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50 text-[9px] font-black uppercase tracking-widest text-slate-400">
                    <th class="px-8 py-4">Nama</th>
                    <th class="px-6 py-4">Lapangan</th>
                    <th class="px-6 py-4">Tanggal Main</th>
                    <th class="px-8 py-4 text-right">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingTerbaru as $booking)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="px-8 py-5 text-xs font-black text-slate-800">
                            {{ $booking->user->name ?? $booking->nama_penyewa ?? 'Pengguna' }}
                        </td>
                        <td class="px-6 py-5 text-xs font-bold text-slate-600">
                            {{ $booking->lapangan->nama_lapangan ?? $booking->nama_gor }}
                        </td>
                        <td class="px-6 py-5 text-xs font-bold text-slate-500">
                            {{ \Illuminate\Support\Carbon::parse($booking->tgl_main)->format('d M Y') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="inline-flex rounded-xl px-3 py-1.5 text-[9px] font-black uppercase tracking-wider
                                {{ $booking->status === 'Success'
                                    ? 'bg-green-50 text-green-700'
                                    : ($booking->status === 'Pending'
                                        ? 'bg-yellow-50 text-yellow-700'
                                        : 'bg-red-50 text-red-700') }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-14 text-center text-xs font-black uppercase tracking-widest text-slate-300">
                            Belum ada booking
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
