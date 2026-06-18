<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>FutsalHub - @yield('title')</title>
</head>
<body class="bg-gray-50 font-sans">
    @if(session('favorite_message'))
        @php
            $favoriteAdded = session('favorite_status') === 'added';
        @endphp
        <div
            id="favorite-toast"
            role="status"
            aria-live="polite"
            class="fixed right-5 top-24 z-[100] w-[calc(100%-2.5rem)] max-w-sm translate-x-0 opacity-100 transition-all duration-500"
        >
            <div class="flex items-start gap-4 rounded-2xl border {{ $favoriteAdded ? 'border-red-100' : 'border-gray-200' }} bg-white p-5 shadow-2xl">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $favoriteAdded ? 'bg-red-50 text-red-500' : 'bg-gray-100 text-gray-500' }}">
                    <i class="{{ $favoriteAdded ? 'fas' : 'far' }} fa-heart text-xl"></i>
                </div>
                <div class="flex-grow pt-0.5">
                    <p class="text-[10px] font-black uppercase tracking-widest {{ $favoriteAdded ? 'text-red-500' : 'text-gray-500' }}">
                        {{ $favoriteAdded ? 'Favorit Ditambahkan' : 'Favorit Dihapus' }}
                    </p>
                    <p class="mt-1 text-xs font-bold leading-relaxed text-gray-700">
                        {{ session('favorite_message') }}
                    </p>
                </div>
                <button
                    type="button"
                    aria-label="Tutup notifikasi"
                    onclick="closeFavoriteToast()"
                    class="text-gray-300 transition hover:text-gray-600"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <script>
            function closeFavoriteToast() {
                const toast = document.getElementById('favorite-toast');

                if (!toast) {
                    return;
                }

                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }

            setTimeout(closeFavoriteToast, 4000);
        </script>
    @endif

    <nav class="bg-white border-b px-8 py-4 flex items-center justify-between sticky top-0 z-50">
        <a href="/dashboard" class="font-black text-blue-900 text-2xl tracking-tighter hover:opacity-80 transition">
            FUTSALHUB
        </a>

        <div class="hidden md:flex space-x-8 items-center">
            <a href="{{ route('dashboard') }}" class="relative py-2 font-medium transition uppercase text-sm tracking-wide {{ request()->routeIs('dashboard') || request()->routeIs('lapangan.show') || request()->routeIs('lapangan.favorites') || request()->routeIs('booking.checkout') ? 'text-blue-700 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:bg-blue-700 after:rounded-full' : 'text-gray-500 hover:text-blue-600' }}">
                CARI GOR
            </a>
            <a href="{{ route('membership.index') }}" class="relative py-2 font-medium transition uppercase text-sm tracking-wide {{ request()->routeIs('membership.index') ? 'text-blue-700 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:bg-blue-700 after:rounded-full' : 'text-gray-500 hover:text-blue-600' }}">
                MEMBER
            </a>
            <a href="{{ route('booking.guide') }}" class="relative py-2 font-medium transition uppercase text-sm tracking-wide {{ request()->routeIs('booking.guide') ? 'text-blue-700 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:bg-blue-700 after:rounded-full' : 'text-gray-500 hover:text-blue-600' }}">
                CARA BOOKING
            </a>
        </div>
        
        <div class="relative group">
            {{-- LOGIKA PENGECEKAN LOGIN --}}
            @auth
                {{-- TAMPILAN JIKA SUDAH LOGIN --}}
                <button class="flex items-center gap-3 focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-green-600 font-bold uppercase mt-1 tracking-widest">Online</p>
                    </div>
                    
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md hover:bg-blue-700 transition uppercase overflow-hidden border-2 border-white">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                </button>

                <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[60]">
                    <div class="p-4 border-b border-gray-50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Menu Akun</p>
                    </div>
                    
                    <a href="{{ route('profil.edit') }}" class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-gray-50 transition uppercase tracking-wider">
                        <i class="fas fa-user-circle text-gray-400 text-lg"></i> Lihat Profil
                    </a>
                    <a href="{{ route('booking.index') }}" class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-gray-50 transition uppercase tracking-wider">
                        <i class="fas fa-history text-gray-400 text-lg"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('lapangan.favorites') }}" class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-gray-50 transition uppercase tracking-wider">
                        <i class="fas fa-heart text-red-400 text-lg"></i> GOR Favorit
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition border-t border-gray-50 uppercase tracking-wider text-left">
                            <i class="fas fa-sign-out-alt text-lg"></i> Logout
                        </button>
                    </form>
                </div>
            @else
                {{-- TAMPILAN JIKA BELUM LOGIN (TAMU/GUEST) --}}
                <a href="{{ route('login') }}" class="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-blue-700 uppercase leading-none">Masuk Akun</p>
                        <p class="text-[8px] text-blue-500 font-bold uppercase mt-1">Gunakan Akun</p>
                    </div>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-sign-in-alt text-xs"></i>
                    </div>
                </a>
            @endauth
        </div>
    </nav> 
    
    <div class="max-w-7xl mx-auto px-6 mt-4">
        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded-2xl font-black uppercase italic text-[10px] tracking-widest shadow-lg shadow-green-100 flex items-center gap-3 animate-bounce">
                <i class="fas fa-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-4 rounded-2xl font-black uppercase italic text-[10px] tracking-widest shadow-lg shadow-red-100 flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-lg"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto flex p-6 gap-8">
        @yield('content')
    </div>

    <footer class="bg-white border-t border-gray-100 mt-20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mb-16">
                <div class="bg-blue-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-blue-100">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-black italic uppercase tracking-tighter mb-4">FutsalHub.</h3>
                        <p class="text-xs font-bold text-blue-200 uppercase tracking-widest leading-relaxed">
                            Solusi booking lapangan futsal tercepat di Bandung. <br> Main bareng tim makin gampang.
                        </p>
                    </div>
                    <i class="fas fa-futbol absolute -right-10 -bottom-10 text-[15rem] text-white opacity-5 transform rotate-12"></i>
                </div>

                <div class="flex flex-col justify-center">
                    <h4 class="text-sm font-black uppercase tracking-[0.3em] text-blue-600 mb-8">Contact Us</h4>
                    
                    <div class="space-y-6">
                        <a href="https://wa.me/628123456789" class="group flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 transition-all">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1 tracking-widest">WhatsApp Admin</p>
                                <p class="text-sm font-bold text-gray-700">+62 89637036514</p>
                            </div>
                        </a>

                        <div class="group flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-all">
                                <i class="far fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1 tracking-widest">Official Email</p>
                                <p class="text-sm font-bold text-gray-700">support@futsalhub.id</p>
                            </div>
                        </div>

                        <div class="group flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-600 transition-all">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1 tracking-widest">Location</p>
                                <p class="text-sm font-bold text-gray-700 uppercase italic">PHH Mustofa, Bandung</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-50 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">© 2026 FutsalHub Indonesia. All Rights Reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-[9px] font-black text-gray-300 uppercase tracking-widest hover:text-blue-600 transition">Privacy Policy</a>
                    <a href="#" class="text-[9px] font-black text-gray-300 uppercase tracking-widest hover:text-blue-600 transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
