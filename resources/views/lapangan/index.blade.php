@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<aside class="w-1/4">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
        <h2 class="font-black text-gray-800 mb-6 flex items-center gap-2 tracking-wide text-sm">
            <i class="fas fa-search text-blue-600"></i> FILTER
        </h2>
        
        <form action="{{ route('dashboard') }}" method="GET">
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="mb-5">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">CARI LAPANGAN</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama GOR..."
                        class="w-full border border-gray-200 py-3 pl-11 pr-3 rounded-xl bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"
                    >
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">PILIH AREA</label>
                <select name="area" class="w-full border border-gray-200 p-3 rounded-xl bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Area</option>
                    <option value="Bandung Pusat" {{ request('area') === 'Bandung Pusat' ? 'selected' : '' }}>Bandung Pusat</option>
                    <option value="Bandung Utara" {{ request('area') === 'Bandung Utara' ? 'selected' : '' }}>Bandung Utara</option>
                    <option value="Bandung Selatan" {{ request('area') === 'Bandung Selatan' ? 'selected' : '' }}>Bandung Selatan</option>
                    <option value="Bandung Timur" {{ request('area') === 'Bandung Timur' ? 'selected' : '' }}>Bandung Timur</option>
                    <option value="Bandung Barat" {{ request('area') === 'Bandung Barat' ? 'selected' : '' }}>Bandung Barat</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">TIPE RUMPUT</label>
                <select name="tipe_rumput" class="w-full border border-gray-200 p-3 rounded-xl bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Tipe</option>
                    <option value="Sintetis" {{ request('tipe_rumput') === 'Sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                    <option value="Interlock" {{ request('tipe_rumput') === 'Interlock' ? 'selected' : '' }}>Interlock</option>
                    <option value="Mat" {{ request('tipe_rumput') === 'Mat' ? 'selected' : '' }}>Vinyl / Mat</option>
                    <option value="Basah" {{ request('tipe_rumput') === 'Basah' ? 'selected' : '' }}>Rumput Alami</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-black py-4 rounded-xl transition shadow-lg shadow-blue-100 flex items-center justify-center gap-2 text-xs tracking-widest uppercase">
                Cari & Filter
            </button>

            @if(request()->hasAny(['search', 'area', 'tipe_rumput']))
                <a href="{{ route('dashboard', array_filter(['sort' => request('sort')])) }}" class="mt-3 flex w-full items-center justify-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-500">
                    Reset Pencarian
                </a>
            @endif
        </form>

  
         <div class="mt-8 pt-8 border-t border-dashed">
            <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                <p class="text-[10px] font-bold text-green-700 uppercase leading-none mb-3 font-black">Benefit Member</p>

                @guest
                    <p class="text-[10px] font-bold text-green-800 leading-relaxed mb-3">
                        Login untuk booking lapangan dan mendapatkan diskon membership.
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center bg-blue-600 text-white rounded-lg py-2 text-[9px] font-black uppercase tracking-widest hover:bg-blue-700 transition">
                        Login Sekarang
                    </a>
                @elseif(Auth::user()->isActiveMember())
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2 text-[10px] font-bold text-green-800 leading-snug">
                            <i class="fas fa-check-circle mt-0.5 text-green-600"></i>
                            Diskon 10% setiap booking lapangan
                        </li>
                        <li class="flex items-start gap-2 text-[10px] font-bold text-green-800 leading-snug">
                            <i class="fas fa-check-circle mt-0.5 text-green-600"></i>
                            Prioritas booking untuk jadwal favorit
                        </li>
                        <li class="flex items-start gap-2 text-[10px] font-bold text-green-800 leading-snug">
                            <i class="fas fa-check-circle mt-0.5 text-green-600"></i>
                            Status member aktif di akun kamu
                        </li>
                    </ul>
                @elseif(Auth::user()->isPendingMember())
                    <p class="text-[10px] font-bold text-yellow-700 leading-relaxed">
                        Pengajuan membership kamu sedang menunggu verifikasi admin.
                    </p>
                @elseif(Auth::user()->isExpiredMember())
                    <p class="text-[10px] font-bold text-red-700 leading-relaxed mb-3">
                        Membership kamu sudah kedaluwarsa. Daftar ulang untuk mendapatkan diskon 10%.
                    </p>
                    <a href="{{ route('membership.index') }}" class="inline-flex w-full items-center justify-center bg-red-600 text-white rounded-lg py-2 text-[9px] font-black uppercase tracking-widest hover:bg-red-700 transition">
                        Daftar Ulang
                    </a>
                @else
                    <p class="text-[10px] font-bold text-green-800 leading-relaxed mb-3">
                        Aktifkan membership untuk mendapatkan diskon 10% setiap booking.
                    </p>
                    <a href="{{ route('membership.index') }}" class="inline-flex w-full items-center justify-center bg-green-600 text-white rounded-lg py-2 text-[9px] font-black uppercase tracking-widest hover:bg-green-700 transition">
                        Daftar Member
                    </a>
                @endguest
            </div>
        </div> 
    
    </div>
</aside>

@if(session('success'))
<a href="{{ route('booking.index') }}" id="notif-booking" class="fixed top-5 right-5 z-[100] group transition-all duration-500">
    <div class="bg-white border-l-4 border-blue-600 shadow-2xl rounded-2xl p-5 flex items-center gap-4 min-w-[320px] hover:bg-blue-50 border border-gray-100">
        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div class="flex-grow">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-none">Pemesanan Berhasil!</p>
            <p class="text-xs font-bold text-gray-700">Klik untuk lihat riwayat & invoice.</p>
        </div>
        <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-600 transition"></i>
    </div>
</a>

<script>
    setTimeout(() => {
        const notif = document.getElementById('notif-booking');
        if(notif) {
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 500);
        }
    }, 5000);
</script>
@endif

@if(session('error'))
<div class="fixed top-5 right-5 z-[100] bg-red-50 border border-red-200 border-l-4 border-l-red-600 shadow-2xl rounded-2xl p-5 min-w-[320px]">
    <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Booking tidak tersedia</p>
    <p class="text-xs font-bold text-red-800">{{ session('error') }}</p>
</div>
@endif

<main class="w-3/4">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-black text-gray-800 tracking-tight italic uppercase">Temukan Gor terbaik</h1>

        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-3">
            @foreach(['search', 'area', 'tipe_rumput'] as $filter)
                @if(request($filter))
                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                @endif
            @endforeach

            <label for="sort" class="whitespace-nowrap text-[10px] font-black uppercase tracking-widest text-gray-400">
                Urutkan
            </label>
            <div class="relative">
                <select
                    id="sort"
                    name="sort"
                    onchange="this.form.submit()"
                    class="appearance-none rounded-xl border border-gray-200 bg-white py-3 pl-4 pr-10 text-xs font-black text-gray-700 outline-none transition hover:border-blue-300 focus:ring-2 focus:ring-blue-500"
                >
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="rating_desc" {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="popular_desc" {{ request('sort') === 'popular_desc' ? 'selected' : '' }}>Terpopuler</option>
                </select>
                <i class="fas fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[9px] text-gray-400"></i>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 gap-8">
        @forelse($lapangan as $item)
            @include('lapangan._card', ['item' => $item, 'favoriteIds' => $favoriteIds])
        @empty
            <div class="col-span-2 rounded-3xl border border-dashed border-gray-200 bg-white p-12 text-center">
                <i class="fas fa-search text-4xl text-gray-300"></i>
                <p class="mt-4 text-sm font-black uppercase text-gray-500">Lapangan tidak ditemukan</p>
                <p class="mt-2 text-xs text-gray-400">Coba gunakan nama atau filter lain.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection
