@extends('layouts.admin')

@section('title', 'Laporan Pendapatan - Admin')

@section('active_menu','pendapatan')

@section('admin_content')
    <div class="flex items-center justify-between mb-10">
        <div>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Ikhtisar Finansial</p>
            <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Laporan Pendapatan</h2>
        </div>
        <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200/60 text-right">
            <p class="text-[9px] text-slate-400 font-black uppercase tracking-wider mb-0.5">Status Sistem Keuangan</p>
            <p class="text-xs font-black text-green-600 uppercase tracking-widest flex items-center gap-2 justify-end">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Terintegrasi Real-time
            </p>
        </div>
    </div>

    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-200/60 max-w-md relative overflow-hidden group">
        <div class="absolute -right-6 -bottom-6 text-slate-50 opacity-[0.03] text-9xl font-black group-hover:scale-110 transition duration-500">
            <i class="fas fa-wallet"></i>
        </div>
        <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest mb-2">Total Omzet Keseluruhan</p>
        <h1 class="text-5xl font-black text-blue-600 tracking-tighter leading-none">
            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
        </h1>
    </div>
@endsection

