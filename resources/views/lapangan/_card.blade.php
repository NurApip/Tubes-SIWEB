@php
    $active = (bool) ($item->is_active ?? true);
    $isFavorite = in_array($item->lapangan_id, $favoriteIds ?? []);
    $ratingAverage = (float) ($item->reviews_avg_rating ?? 0);
    $ratingCount = (int) ($item->reviews_count ?? 0);
    $fullStars = (int) floor($ratingAverage);
@endphp

<div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300">
    <div class="h-48 bg-gray-100 relative flex items-center justify-center overflow-hidden">
        @if($item->foto)
            <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $item->nama_lapangan }}">
        @else
            <i class="fas fa-futbol text-6xl text-gray-300 group-hover:scale-110 transition duration-500"></i>
        @endif

        @auth
        <form action="{{ route('lapangan.favorite', $item->lapangan_id) }}" method="POST" class="absolute right-4 top-4 z-10">
            @csrf
            <button
                type="submit"
                title="{{ $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}"
                aria-label="{{ $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}"
                class="flex h-11 w-11 items-center justify-center rounded-full bg-white/95 text-2xl shadow-lg backdrop-blur transition hover:scale-110"
            >
                <span class="{{ $isFavorite ? 'text-red-500' : 'text-gray-400' }}">{{ $isFavorite ? '♥' : '♡' }}</span>
            </button>
        </form>
        @else
        <a
            href="{{ route('login') }}"
            title="Login untuk menambahkan favorit"
            aria-label="Login untuk menambahkan favorit"
            class="absolute right-4 top-4 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/95 text-gray-400 shadow-lg backdrop-blur transition hover:scale-110 hover:text-red-500"
        >
            <i class="far fa-heart text-lg"></i>
        </a>
        @endauth
    </div>

    <div class="p-6">
        <div class="flex justify-between items-start mb-3">
            <h3 class="font-black text-xl text-gray-800 uppercase tracking-tight">{{ $item->nama_lapangan }}</h3>
            <span class="{{ $active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-[10px] px-2 py-1 rounded-lg font-black uppercase tracking-tighter">
                {{ $active ? 'Arena Aktif' : 'Arena Non-Aktif' }}
            </span>
        </div>

        <p class="text-xs text-gray-400 uppercase font-black tracking-wider mb-2">
            <i class="fas fa-map-marker-alt text-blue-500"></i> {{ $item->lokasi }}
        </p>
        <div class="mb-3 flex items-center gap-2">
            <div class="flex gap-0.5 text-yellow-400">
                @for($star = 1; $star <= 5; $star++)
                    <i class="{{ $star <= $fullStars ? 'fas' : 'far' }} fa-star text-xs"></i>
                @endfor
            </div>
            @if($ratingCount > 0)
                <span class="text-[10px] font-black text-gray-600">{{ number_format($ratingAverage, 1) }} ({{ $ratingCount }} review)</span>
            @else
                <span class="text-[10px] font-bold text-gray-400">0 review</span>
            @endif
        </div>
        <p class="text-xs text-gray-500 mb-6 line-clamp-2 italic leading-relaxed">{{ $item->fasilitas }}</p>

        <div class="flex items-center justify-between border-t border-gray-50 pt-5">
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Mulai Dari</p>
                <p class="font-black text-blue-700 text-xl tracking-tighter">Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('lapangan.show', $item->lapangan_id) }}" class="inline-block {{ $active ? 'bg-gray-100 group-hover:bg-blue-700 group-hover:text-white text-gray-800' : 'bg-red-50 text-red-600' }} px-5 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition shadow-sm text-center">
                {{ $active ? 'Lihat Jadwal' : 'Lihat Detail' }}
            </a>
        </div>
    </div>
</div>
