@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('active_menu','dashboard')

@section('admin_content')
    <div class="mb-10">
        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Ringkasan Sistem</p>
        <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Dashboard Admin</h2>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Uang Masuk</p>
            <h3 class="text-2xl font-black mt-2 text-slate-800">Rp {{ number_format($totalUang) }}</h3>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Member</p>
            <h3 class="text-2xl font-black mt-2 text-slate-800">{{ $totalMember }} User</h3>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Booking</p>
            <h3 class="text-2xl font-black mt-2 text-slate-800">{{ $totalSesi }} Sesi</h3>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
        <h3 class="font-black uppercase mb-4 text-slate-700 text-sm tracking-wide">Daftar Pengguna Sistem</h3>
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-slate-400 uppercase text-[10px] tracking-widest border-b">
                    <th class="pb-4">Nama</th>
                    <th class="pb-4">Email/HP</th>
                    <th class="pb-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b last:border-0">
                    <td class="py-4 font-bold text-slate-800">{{ $user->name }}</td>
                    <td class="py-4 text-slate-600">{{ $user->email ?? $user->hp }}</td>
                    <td class="py-4">
                        <span class="{{ $user->role == 1 ? 'text-blue-600 font-black' : 'text-slate-500 font-bold' }} text-xs uppercase">
                            {{ $user->role == 1 ? 'Admin' : 'Member' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection


