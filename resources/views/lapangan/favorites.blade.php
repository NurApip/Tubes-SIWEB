@extends('layouts.app')

@section('title', 'GOR Favorit')

@section('content')
<main class="w-full">
    <div class="mb-8 flex items-end justify-between">
        <div>
            <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-red-500">Koleksi Pilihanmu</p>
            <h1 class="text-3xl font-black uppercase italic tracking-tight text-gray-800">GOR Favorit</h1>
        </div>
        <a href="{{ route('dashboard') }}" class="rounded-xl bg-gray-100 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-gray-600 transition hover:bg-blue-600 hover:text-white">
            Cari GOR Lain
        </a>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse($lapangan as $item)
            @include('lapangan._card', ['item' => $item, 'favoriteIds' => $favoriteIds])
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-gray-200 bg-white p-16 text-center">
                <div class="text-5xl text-gray-300">♡</div>
                <p class="mt-4 text-sm font-black uppercase text-gray-500">Belum ada GOR favorit</p>
                <p class="mt-2 text-xs text-gray-400">Tekan ikon hati pada card GOR untuk menyimpannya.</p>
                <a href="{{ route('dashboard') }}" class="mt-6 inline-flex rounded-xl bg-blue-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700">
                    Cari GOR
                </a>
            </div>
        @endforelse
    </div>
</main>
@endsection
