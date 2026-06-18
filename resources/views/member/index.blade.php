@extends('layouts.app')

@section('title', 'Membership')

@section('content')
@php
    $user = Auth::user();
    $isActiveMember = $user && $user->isActiveMember();
    $isPendingMember = $user && $user->isPendingMember();
    $isExpiredMember = $user && $user->isExpiredMember();
@endphp

<div class="w-full py-8">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-black uppercase italic tracking-tighter text-gray-800">Exclusive Membership</h2>
        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.4em] mt-2">Dapatkan Harga Khusus & Prioritas Booking</p>
    </div>

    @if(session('success'))
        <div class="max-w-4xl mx-auto mb-8 bg-green-50 border border-green-200 text-green-700 p-4 rounded-2xl text-sm font-black text-center">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-4xl mx-auto mb-8 bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-sm font-black text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-xl shadow-gray-100/50">
            <h3 class="text-xl font-black text-gray-800 uppercase italic mb-6">Keuntungan Member:</h3>
            <ul class="space-y-4">
                <li class="flex items-center gap-3 text-sm font-bold text-gray-600">
                    <i class="fas fa-check-circle text-green-500"></i> Diskon 10% Setiap Booking
                </li>
                <li class="flex items-center gap-3 text-sm font-bold text-gray-600">
                    <i class="fas fa-check-circle text-green-500"></i> Booking 2 Minggu Sebelumnya
                </li>
                <li class="flex items-center gap-3 text-sm font-bold text-gray-600">
                    <i class="fas fa-check-circle text-green-500"></i> Gratis Air Mineral Setiap Main
                </li>
            </ul>

            <div class="mt-8 p-5 rounded-2xl bg-gray-50 border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Status Membership</p>

                @if($isActiveMember)
                    <div>
                        <span class="bg-green-50 text-green-700 text-[10px] px-4 py-2 rounded-xl font-black uppercase border border-green-200">
                            Member Aktif
                        </span>
                        <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-gray-500">
                            Berlaku sampai {{ $user->membership_expires_at->translatedFormat('d F Y') }}
                        </p>
                    </div>
                @elseif($isPendingMember)
                    <span class="bg-yellow-50 text-yellow-700 text-[10px] px-4 py-2 rounded-xl font-black uppercase border border-yellow-200">
                        Menunggu Verifikasi Admin
                    </span>
                @elseif($isExpiredMember)
                    <div>
                        <span class="bg-red-50 text-red-700 text-[10px] px-4 py-2 rounded-xl font-black uppercase border border-red-200">
                            Membership Kedaluwarsa
                        </span>
                        <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-gray-500">
                            Berakhir pada {{ $user->membership_expires_at->translatedFormat('d F Y') }}
                        </p>
                    </div>
                @else
                    <span class="bg-gray-100 text-gray-600 text-[10px] px-4 py-2 rounded-xl font-black uppercase border border-gray-200">
                        Regular Customer
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-blue-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-blue-200">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest mb-2">Biaya Pendaftaran</p>
                <h4 class="text-4xl font-black mb-6 tracking-tighter">Rp 50.000 <span class="text-xs font-normal opacity-60">/Bulan</span></h4>

                <div class="bg-white/10 border border-white/20 p-5 rounded-2xl mb-6">
                    <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-2">Transfer Ke</p>
                    <p class="text-lg font-black tracking-widest">Mandiri 1234-5678-90</p>
                    <p class="text-[10px] text-blue-100 mt-1">a/n FutsalHub</p>
                </div>

                @if($isActiveMember)
                    <button disabled class="w-full bg-green-400 text-green-950 font-black py-4 rounded-2xl uppercase tracking-widest cursor-not-allowed">
                        Member Aktif
                    </button>
                @elseif($isPendingMember)
                    <button disabled class="w-full bg-yellow-300 text-yellow-900 font-black py-4 rounded-2xl uppercase tracking-widest cursor-not-allowed">
                        Menunggu Verifikasi
                    </button>
                @else
                    <form action="{{ route('membership.join') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-[10px] font-black text-blue-200 uppercase tracking-widest mb-2">
                                Upload Bukti Transfer
                            </label>
                            <input type="file" name="bukti_membership" accept="image/*" required
                                class="w-full bg-white text-blue-900 p-4 rounded-2xl text-xs font-bold">
                        </div>

                        <button type="submit" class="w-full bg-white text-blue-900 font-black py-4 rounded-2xl uppercase tracking-widest hover:bg-blue-50 transition shadow-lg active:scale-95">
                            {{ $isExpiredMember ? 'Kirim Bukti & Daftar Ulang' : 'Kirim Bukti & Daftar Member' }}
                        </button>
                    </form>
                @endif
            </div>

            <i class="fas fa-crown absolute -right-8 -bottom-8 text-white opacity-10 text-[12rem] transform rotate-12"></i>
        </div>
    </div>
</div>
@endsection
