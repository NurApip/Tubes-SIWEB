@extends('layouts.admin')

@section('title', 'Laporan Pendapatan - Admin')

@section('active_menu','pendapatan')

@section('admin_content')
<div class="mb-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
    <div>
        <p class="mb-1 text-[10px] font-black uppercase tracking-widest text-slate-400">Ikhtisar Finansial</p>
        <h2 class="text-4xl font-black uppercase italic tracking-tighter text-gray-800">Laporan Pendapatan</h2>
    </div>
    <div class="w-fit rounded-2xl border border-slate-200 bg-white px-6 py-3 text-right shadow-sm">
        <p class="mb-0.5 text-[9px] font-black uppercase tracking-wider text-slate-400">Status Sistem Keuangan</p>
        <p class="flex items-center justify-end gap-2 text-xs font-black uppercase tracking-widest text-green-600">
            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>
            Terintegrasi Real-time
        </p>
    </div>
</div>

<section class="mb-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="relative overflow-hidden rounded-[2.5rem] border border-blue-100 bg-blue-600 p-9 text-white shadow-xl shadow-blue-100 lg:col-span-2">
        <div class="relative z-10">
            <p class="mb-3 text-[10px] font-black uppercase tracking-[0.25em] text-blue-200">Total Pendapatan Terverifikasi</p>
            <h1 class="text-5xl font-black leading-none tracking-tighter">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </h1>
            <p class="mt-5 text-[10px] font-bold uppercase tracking-widest text-blue-100">
                Hanya berasal dari booking berstatus Success
            </p>
        </div>
        <i class="fas fa-wallet absolute -bottom-10 -right-6 text-[12rem] text-white opacity-10"></i>
    </div>

    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-9 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600">
            <i class="fas fa-receipt"></i>
        </div>
        <p class="mt-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Transaksi Berhasil</p>
        <p class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $bookings->count() }}</p>
        <p class="mt-2 text-[9px] font-bold uppercase tracking-wider text-green-600">Booking Success</p>
    </div>
</section>

<section class="mb-10 rounded-[2.5rem] border border-slate-200 bg-white p-9 shadow-sm">
    <div class="mb-8">
        <p class="text-[9px] font-black uppercase tracking-[0.25em] text-blue-600">Analitik Lapangan</p>
        <h3 class="mt-1 text-xl font-black uppercase tracking-tight text-slate-800">Pendapatan per Lapangan</h3>
        <p class="mt-2 text-xs font-medium text-slate-400">Perbandingan omzet dari seluruh booking yang sudah berhasil.</p>
    </div>

    <div class="space-y-6">
        @forelse($pendapatanPerLapangan as $lapangan => $total)
            @php
                $barWidth = max(6, round(($total / $pendapatanTertinggi) * 100));
            @endphp
            <div>
                <div class="mb-2 flex items-end justify-between gap-4">
                    <p class="truncate text-xs font-black uppercase text-slate-700">{{ $lapangan }}</p>
                    <p class="whitespace-nowrap text-xs font-black text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <div class="h-4 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full bg-gradient-to-r from-blue-600 to-cyan-400 transition-all duration-700"
                        style="width: {{ $barWidth }}%"
                        title="{{ $lapangan }}: Rp {{ number_format($total, 0, ',', '.') }}"
                    ></div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border-2 border-dashed border-slate-100 p-12 text-center">
                <i class="fas fa-chart-bar text-4xl text-slate-200"></i>
                <p class="mt-4 text-xs font-black uppercase tracking-widest text-slate-300">Belum ada pendapatan</p>
            </div>
        @endforelse
    </div>
</section>

<section class="overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-9 py-7">
        <p class="text-[9px] font-black uppercase tracking-[0.25em] text-blue-600">Transaksi Success</p>
        <h3 class="mt-1 text-xl font-black uppercase tracking-tight text-slate-800">Riwayat Pendapatan</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px] text-left">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50 text-[9px] font-black uppercase tracking-widest text-slate-400">
                    <th class="px-7 py-4">Kode Tiket</th>
                    <th class="px-5 py-4">Tanggal</th>
                    <th class="px-5 py-4">Nama User</th>
                    <th class="px-5 py-4">Lapangan</th>
                    <th class="px-5 py-4">Jam Main</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-7 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-b border-slate-50 transition hover:bg-slate-50/60">
                        <td class="px-7 py-5 text-xs font-black tracking-wide text-blue-600">
                            {{ $booking->kode_tiket ?: '-' }}
                        </td>
                        <td class="px-5 py-5 text-xs font-bold text-slate-500">
                            {{ $booking->updated_at->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-5 text-xs font-black text-slate-700">
                            {{ $booking->user->name ?? $booking->nama_penyewa ?? 'Pengguna' }}
                        </td>
                        <td class="px-5 py-5 text-xs font-bold text-slate-600">
                            {{ $booking->lapangan->nama_lapangan ?? $booking->nama_gor }}
                        </td>
                        <td class="px-5 py-5 text-xs font-bold text-slate-600">
                            {{ substr($booking->jam_mulai, 0, 5) }} - {{ $booking->jam_selesai_label }}
                        </td>
                        <td class="px-5 py-5">
                            <span class="inline-flex items-center gap-2 rounded-xl bg-green-50 px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-green-700">
                                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                Success
                            </span>
                        </td>
                        <td class="px-7 py-5 text-right text-xs font-black text-slate-800">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-16 text-center">
                            <i class="fas fa-receipt text-4xl text-slate-200"></i>
                            <p class="mt-4 text-xs font-black uppercase tracking-widest text-slate-300">Belum ada riwayat pendapatan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
