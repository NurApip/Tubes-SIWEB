@extends('layouts.app')

@section('title', 'Detail ' . $lapangan->nama_lapangan)

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 pb-12">
    <a href="/dashboard" class="inline-flex items-center text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 hover:text-blue-600 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>

    {{-- HEADER GAMBAR --}}
    <div class="bg-gray-900 rounded-[3rem] overflow-hidden shadow-2xl mb-8 border-4 border-white h-[500px] flex flex-col md:flex-row relative">
        <div class="w-full md:w-3/4 h-full relative group">
            @if($lapangan->foto)
            <img id="mainFoto" src="{{ asset('storage/' . $lapangan->foto) }}"
                class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
            @else
            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                <i class="fas fa-futbol text-9xl text-gray-700"></i>
            </div>
            @endif

            <div class="absolute top-8 left-8 bg-blue-900/90 backdrop-blur-md text-white px-8 py-5 rounded-[2rem] font-black shadow-2xl border border-blue-400/20">
                <p class="text-[10px] uppercase opacity-60 mb-1 tracking-[0.2em] leading-none italic">Sewa Per Jam</p>
                <p class="text-3xl leading-none font-black tracking-tighter">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- GALERI TAMBAHAN --}}
        <div class="w-full md:w-1/4 p-6 flex md:flex-col gap-4 overflow-auto bg-gray-950">
            <p class="hidden md:block text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2">Galeri Foto</p>

            @if($lapangan->foto)
            <img src="{{ asset('storage/' . $lapangan->foto) }}"
                class="w-24 h-20 md:w-full md:h-28 object-cover rounded-2xl cursor-pointer hover:ring-4 hover:ring-blue-500 transition border-2 border-blue-500"
                onclick="document.getElementById('mainFoto').src=this.src;">
            @endif

            @if(isset($lapangan->galeri) && $lapangan->galeri->count() > 0)
            @foreach($lapangan->galeri as $foto)
            <img src="{{ asset('storage/' . $foto->path_foto) }}"
                class="w-24 h-20 md:w-full md:h-28 object-cover rounded-2xl cursor-pointer opacity-50 hover:opacity-100 hover:ring-4 hover:ring-blue-500 transition border-2 border-transparent"
                onclick="document.getElementById('mainFoto').src=this.src;">
            @endforeach
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8 md:p-12">
        <div class="flex flex-col lg:flex-row gap-12">

            <div class="flex-grow">
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-blue-50 text-blue-700 text-[10px] px-5 py-2 rounded-xl font-black uppercase tracking-widest border border-blue-100">
                        Rumput: {{ $lapangan->tipe_rumput == 'Mat' ? 'Vinyl / Mat' : ($lapangan->tipe_rumput == 'Basah' ? 'Rumput Alami' : ($lapangan->tipe_rumput == 'Sintetis' ? 'Rumput Sintetis' : $lapangan->tipe_rumput)) }}
                    </span>
                    <span class="flex items-center gap-2 text-[10px] font-black text-green-600 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Arena Aktif
                    </span>
                </div>

                <h1 class="text-5xl font-black text-gray-800 uppercase italic tracking-tighter mb-8 leading-none">
                    {{ $lapangan->nama_lapangan }}
                </h1>

                @php
                    $mapsUrl = $lapangan->link_maps ?: 'https://www.google.com/maps/search/' . urlencode(trim($lapangan->nama_lapangan . ' ' . ($lapangan->alamat_lengkap ?: $lapangan->lokasi)));
                @endphp

                <div class="bg-gray-50 border border-gray-100 p-6 rounded-[2rem] flex items-start gap-5 mb-10 shadow-inner">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-200 shrink-0">
                        <i class="fas fa-map-marked-alt text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="text-[10px] font-black text-blue-900 uppercase tracking-[0.2em] mb-1">Titik Lokasi Arena</p>
                        <p class="text-sm font-black text-gray-800 uppercase italic">{{ $lapangan->lokasi ?? 'Area Bandung Pusat' }}</p>

                        <p class="text-[11px] text-gray-500 font-medium mt-1 leading-relaxed">
                            {{ $lapangan->alamat_lengkap ?: 'Alamat lengkap belum diisi admin.' }}
                        </p>

                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-4 text-[10px] font-black bg-blue-600 text-white px-5 py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100 uppercase tracking-widest">
                            <i class="fas fa-directions"></i> Buka Google Maps
                        </a>
                    </div>
                </div>

                {{-- JADWAL OPERASIONAL --}}
                @php
                $slotDefinitions = [
                'Pagi' => ['label' => 'Pagi', 'time' => '08:00 - 12:00'],
                'Siang' => ['label' => 'Siang', 'time' => '13:00 - 17:00'],
                'Sore' => ['label' => 'Sore', 'time' => '18:00 - 20:00'],
                'Malam' => ['label' => 'Malam', 'time' => '21:00 - 23:00'],
                ];
                $operasionalMap = collect($operasionalToday ?? [])->keyBy('slot');
                $jamToSlot = [
                '08:00' => 'Pagi',
                '09:00' => 'Pagi',
                '16:00' => 'Siang',
                '19:00' => 'Malam',
                '20:00' => 'Malam',
                ];
                @endphp
                <div class="mb-10">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5">Status Operasional Hari Ini</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($slotDefinitions as $slot => $meta)
                        @php
                        $row = $operasionalMap->get($slot);
                        $isFull = $row ? (bool)($row->is_full ?? false) : false;
                        @endphp
                        <div class="p-4 rounded-2xl text-center shadow-sm {{ $isFull ? 'bg-red-50 border border-red-100 opacity-90' : 'bg-white border border-gray-100' }}">
                            <p class="text-[9px] font-black uppercase mb-1 {{ $isFull ? 'text-red-600' : 'text-gray-400' }}">{{ $meta['label'] }}</p>
                            <p class="text-xs font-black italic {{ $isFull ? 'text-gray-400 line-through' : 'text-gray-700' }}">{{ $meta['time'] }}</p>
                            <p class="mt-3 text-[10px] font-black uppercase tracking-[0.2em] {{ $isFull ? 'text-red-700' : 'text-green-700' }}">
                                {{ $isFull ? 'Penuh' : 'Tersedia' }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Keunggulan Fasilitas</p>
                        <div class="flex flex-wrap gap-3">
                            @php
                            $fasilitasArray = array_filter(array_map(function ($item) {
                            return trim((string)$item);
                            }, explode(',', (string) ($lapangan->fasilitas ?? '-'))));
                            @endphp
                            @forelse($fasilitasArray as $f)
                            @if($f !== '-' && $f !== '')
                            <span class="bg-gray-50 border border-gray-100 px-5 py-3 rounded-2xl text-[10px] font-black text-gray-600 uppercase flex items-center gap-3 italic">
                                <i class="fas fa-check-circle text-blue-600 text-sm"></i> {{ $f }}
                            </span>
                            @endif
                            @empty
                            <span class="text-xs text-gray-400 italic">Tidak ada fasilitas tambahan.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-3xl border-l-4 border-blue-600">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi Tambahan</p>
                        <p class="text-gray-500 text-xs font-bold italic leading-loose uppercase tracking-tighter">
                            {{ $lapangan->deskripsi ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- FORM RESERVASI --}}
            <div class="w-full lg:w-96">
                <div class="bg-blue-700 rounded-[3rem] p-10 shadow-2xl text-white sticky top-24 border-b-8 border-blue-900">
                    <h3 class="text-center text-[10px] font-black uppercase tracking-[0.4em] mb-10 opacity-70 italic">Reservasi Slot</h3>

                    <form action="{{ route('booking.checkout', $lapangan->lapangan_id) }}" method="GET" class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-blue-200 uppercase mb-3 ml-2 tracking-widest leading-none">1. Tanggal Tanding</label>
                            <input type="date" name="tgl_main" required
                                class="w-full bg-white border-none p-5 rounded-2xl text-xs font-black text-gray-800 focus:ring-4 focus:ring-blue-400 outline-none shadow-xl shadow-blue-900/20">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-blue-200 uppercase mb-3 ml-2 tracking-widest leading-none">
                                2. Jam Kick-Off
                            </label>

                            <input type="hidden" name="jam_mulai" id="jam_mulai" required>

                            <div class="grid grid-cols-2 gap-3">
                                @foreach(['08:00' => '08:00 WIB', '09:00' => '09:00 WIB', '16:00' => '16:00 WIB', '19:00' => '19:00 WIB', '20:00' => '20:00 WIB'] as $value => $label)
                                @php
                                $slot = $jamToSlot[$value] ?? null;
                                $row = $slot ? $operasionalMap->get($slot) : null;
                                $isDisabled = $row ? (bool)($row->is_full ?? false) : false;
                                @endphp

                                <button
                                    type="button"
                                    data-jam="{{ $value }}"
                                    class="slot-jam p-4 rounded-2xl text-xs font-black border-2 transition-all duration-300
                    {{ $isDisabled 
                        ? 'bg-red-100 text-red-500 border-red-200 opacity-60 cursor-not-allowed line-through' 
                        : 'bg-white text-gray-800 border-white hover:bg-black hover:text-white hover:border-black shadow-xl shadow-blue-900/20' 
                    }}"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    <div>{{ $label }}</div>
                                    <div class="text-[8px] mt-1 uppercase tracking-widest">
                                        {{ $isDisabled ? 'Penuh' : 'Tersedia' }}
                                    </div>
                                </button>
                                @endforeach
                            </div>

                            <p id="jam-error" class="hidden text-[9px] text-red-200 uppercase tracking-[0.2em] mt-3 font-black">
                                Pilih salah satu jam terlebih dahulu.
                            </p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-blue-200 uppercase mb-3 ml-2 tracking-widest leading-none">3. Nama Penyewa / Tim</label>
                            <input type="text" name="nama_penyewa" required placeholder="Contoh: Tim Macan Bandung"
                                class="w-full bg-white border-none p-5 rounded-2xl text-xs font-black text-gray-800 focus:ring-4 focus:ring-blue-400 outline-none shadow-xl shadow-blue-900/20">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-blue-200 uppercase mb-3 ml-2 tracking-widest leading-none">4. Nomor WhatsApp</label>
                            <input type="text" name="nomor_wa" required placeholder="081234567890"
                                class="w-full bg-white border-none p-5 rounded-2xl text-xs font-black text-gray-800 focus:ring-4 focus:ring-blue-400 outline-none shadow-xl shadow-blue-900/20">
                            <p class="text-[9px] text-gray-200 uppercase tracking-[0.3em] mt-2">Gunakan nomor WA aktif untuk konfirmasi admin.</p>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full bg-white hover:bg-black hover:text-white text-blue-700 font-black py-6 rounded-2xl text-[11px] uppercase tracking-[0.2em] transition-all duration-300 shadow-xl active:scale-95 flex items-center justify-center gap-3">
                                Konfirmasi Jadwal <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const jamInput = document.getElementById('jam_mulai');
        const buttons = document.querySelectorAll('.slot-jam');
        const jamError = document.getElementById('jam-error');

        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                if (button.disabled) {
                    return;
                }

                buttons.forEach(function(btn) {
                    btn.classList.remove('bg-black', 'text-white', 'border-black', 'scale-95');
                    btn.classList.add('bg-white', 'text-gray-800', 'border-white');
                });

                button.classList.remove('bg-white', 'text-gray-800', 'border-white');
                button.classList.add('bg-black', 'text-white', 'border-black', 'scale-95');

                jamInput.value = button.dataset.jam;
                jamError.classList.add('hidden');
            });
        });

        form.addEventListener('submit', function(e) {
            if (!jamInput.value) {
                e.preventDefault();
                jamError.classList.remove('hidden');
            }
        });
    });
</script>
@endsection
