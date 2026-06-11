<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="flex bg-slate-100 min-h-screen font-sans">

    {{-- Sidebar (berpatokan dari admin/pendapatan.blade.php) --}}
    <div class="w-80 bg-gray-900 text-white p-8 flex flex-col justify-between shrink-0">
        <div>
            <div class="mb-12">
                <h1 class="text-2xl font-black italic tracking-tighter text-blue-500">
                    FUTSALHUB
                    <span class="text-white text-xs block not-italic font-medium tracking-widest uppercase opacity-40 mt-1">
                        Admin Panel
                    </span>
                </h1>
            </div>

            <nav class="space-y-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider {{ (trim($__env->yieldContent('active_menu')) ?? '') === 'dashboard' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/10 transition' : 'text-gray-400 hover:bg-gray-800 hover:text-white transition' }}">

                    <i class="fas fa-th-large text-base w-5 text-center"></i> Dashboard
                </a>


                <a href="{{ route('admin.fields') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider {{ (trim($__env->yieldContent('active_menu')) ?? '') === 'fields' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/10 transition' : 'text-gray-400 hover:bg-gray-800 hover:text-white transition' }}">
                    <i class="fas fa-futbol text-base w-5 text-center"></i> Kelola Lapangan
                </a>


                <a href="{{ route('admin.members') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider {{ (trim($__env->yieldContent('active_menu')) ?? '') === 'members' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/10 transition' : 'text-gray-400 hover:bg-gray-800 hover:text-white transition' }}">
                    <i class="fas fa-users text-base w-5 text-center"></i> Akun Member
                </a>

                <a href="{{ route('admin.bookings') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider {{ (trim($__env->yieldContent('active_menu')) ?? '') === 'bookings' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/10 transition' : 'text-gray-400 hover:bg-gray-800 hover:text-white transition' }}">
                    <i class="fas fa-ticket-alt text-base w-5 text-center"></i> Booking
                </a>

                <a href="{{ route('admin.operasional') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider {{ (trim($__env->yieldContent('active_menu')) ?? '') === 'operasional' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/10 transition' : 'text-gray-400 hover:bg-gray-800 hover:text-white transition' }}">
                    <i class="fas fa-clock text-base w-5 text-center"></i> Operasional Slot
                </a>

                <a href="{{ route('admin.pendapatan') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider {{ (trim($__env->yieldContent('active_menu')) ?? '') === 'pendapatan' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/10 transition' : 'text-gray-400 hover:bg-gray-800 hover:text-white transition' }}">
                    <i class="fas fa-wallet text-base w-5 text-center"></i> Laporan Pendapatan
                </a>



            </nav>
        </div>

        <div class="border-t border-gray-800 pt-6">
            <div class="flex items-center gap-4 px-2 mb-4">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-black uppercase italic shadow-lg shadow-blue-600/20">
                    A
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wide leading-none mb-1">Admin</h4>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-green-500">Online</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-wider text-red-400 hover:bg-red-950/30 transition text-left">
                    <i class="fas fa-sign-out-alt text-base w-5 text-center"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </div>

    <main class="flex-1 p-12">
        @yield('admin_content')
    </main>

</body>
</html>

