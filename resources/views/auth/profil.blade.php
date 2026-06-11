@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="w-full max-w-5xl mx-auto">
    
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl text-xs font-black uppercase tracking-widest shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('profil.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            
            <div class="w-full md:w-1/3 bg-blue-900 p-8 flex flex-col items-center justify-center text-center">
                <div class="relative group mb-6">
                    <div class="w-32 h-32 bg-blue-600 rounded-full border-4 border-white/30 flex items-center justify-center text-white text-4xl font-black shadow-2xl uppercase overflow-hidden">
    @if(Auth::user()->foto)
        <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="w-full h-full object-cover">
    @else
        {{ substr(Auth::user()->name, 0, 1) }}
    @endif
</div>
                    <label id="photo-label" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 hidden cursor-pointer rounded-full transition text-[10px] font-bold uppercase">
                        Ganti Foto
                        <input type="file" name="foto" class="hidden">
                    </label>
                </div>
                
                <h1 class="text-xl font-black text-white uppercase tracking-tight mb-1">{{ Auth::user()->name }}</h1>
                <p class="text-blue-300 text-[10px] font-bold uppercase tracking-[0.2em] mb-6">Customer Terverifikasi</p>
                
                <div class="w-full pt-6 border-t border-white/10 text-left">
                    <p class="text-[9px] font-black text-blue-400 uppercase mb-2">Statistik Akun</p>
                    <div class="grid grid-cols-2 gap-2 text-white">
                        <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                            <p class="text-[10px] opacity-60 uppercase">Booking</p>
                            <p class="font-bold">12</p>
                        </div>
                        <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                            <p class="text-[10px] opacity-60 uppercase">Loyalty</p>
                            <p class="font-bold">2.4k</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-2/3 p-8 md:p-12">
                <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Detail Informasi Akun
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 text-gray-400">Nama Pengguna</label>
                        <input type="text" id="input-name" name="name" value="{{ Auth::user()->name }}" 
                            class="w-full border border-gray-100 p-4 rounded-2xl bg-gray-50 text-sm font-bold text-gray-700 outline-none transition" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 text-gray-400">Email Aktif</label>
                        <input type="email" id="input-email" name="email" value="{{ Auth::user()->email }}" 
                            class="w-full border border-gray-100 p-4 rounded-2xl bg-gray-50 text-sm font-bold text-gray-700 outline-none transition" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 text-gray-400">Nomor WhatsApp</label>
                        <input type="text" id="input-phone" name="no_hp" value="{{ Auth::user()->no_hp ?? '-' }}" 
                            class="w-full border border-gray-100 p-4 rounded-2xl bg-gray-50 text-sm font-bold text-gray-700 outline-none transition" readonly>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-50 flex gap-4">
                    <button type="button" id="btn-edit" onclick="enableEdit()" class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-black py-4 rounded-2xl text-xs uppercase tracking-widest transition shadow-lg shadow-blue-100">
                        <i class="fas fa-edit mr-2"></i> Edit Profil
                    </button>

                    <button type="submit" id="btn-save" class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white font-black py-4 rounded-2xl text-xs uppercase tracking-widest transition shadow-lg shadow-green-100">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>

                    <a href="/dashboard" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-center font-black py-4 rounded-2xl text-xs uppercase tracking-widest transition leading-[3rem]">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function enableEdit() {
        // Aktifkan Input
        document.getElementById('input-name').readOnly = false;
        document.getElementById('input-email').readOnly = false;
        document.getElementById('input-phone').readOnly = false;

        // Ubah Style Visual agar terlihat bisa diisi
        const inputs = ['input-name', 'input-email', 'input-phone'];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            el.classList.remove('bg-gray-50', 'border-gray-100');
            el.classList.add('bg-white', 'border-blue-500', 'ring-4', 'ring-blue-50/50');
        });

        // Tampilkan Label Ganti Foto
        document.getElementById('photo-label').classList.remove('hidden');
        document.getElementById('photo-label').classList.add('opacity-100');

        // Toggle Tombol
        document.getElementById('btn-edit').classList.add('hidden');
        document.getElementById('btn-save').classList.remove('hidden');
    }
</script>
@endsection