@extends('layouts.admin')

@section('title', 'Operasional - Admin')

@section('active_menu','operasional')

@section('admin_content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Manajemen Slot Operasional</p>
                <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Status Operasional Hari Ini</h2>
            </div>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">Status Ketersediaan</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-xs font-black uppercase tracking-widest text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-xs font-black uppercase tracking-widest text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-[32px] shadow-sm border border-slate-200">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Lapangan</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Pagi (08:00-12:00)</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Siang (13:00-17:00)</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Sore (18:00-20:00)</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Malam (21:00-23:00)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lapangans as $lapangan)
                    @php
                        $operasionalMap = ($lapangan->operasionalToday ?? collect())->keyBy('slot');
                        $arenaActive = (bool) ($lapangan->is_active ?? true);
                    @endphp
                    <tr class="border-b border-slate-100 {{ $arenaActive ? 'hover:bg-slate-50/30' : 'bg-red-50/30' }}">
                        <td class="px-6 py-4">
                            <div class="font-black uppercase text-sm">{{ $lapangan->nama_lapangan }}</div>
                            <div class="text-[10px] text-slate-500 uppercase">{{ $lapangan->lokasi }}</div>
                            <span class="mt-2 inline-flex rounded-full px-3 py-1 text-[9px] font-black uppercase {{ $arenaActive ? 'bg-green-50 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $arenaActive ? 'Arena Aktif' : 'Arena Nonaktif' }}
                            </span>
                        </td>

                        @foreach(['Pagi','Siang','Sore','Malam'] as $slot)
                            @php
                                $row = $operasionalMap->get($slot);
                                $isFull = $row ? (bool)($row->is_full ?? false) : false;
                            @endphp

                            <td class="px-6 py-4">
                                @if(!$arenaActive)
                                    <div class="w-44 inline-flex items-center justify-between px-4 py-3 rounded-2xl border border-red-200 bg-red-50 opacity-90">
                                        <span class="text-[10px] font-black uppercase text-red-700">Tutup</span>
                                        <span class="text-[10px] font-black uppercase text-red-500">Otomatis</span>
                                    </div>
                                @else
                                <form action="{{ route('admin.operasional.update', $lapangan->lapangan_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="slot" value="{{ $slot }}">
                                    <input type="hidden" name="state" value="{{ $isFull ? '0' : '1' }}">

                                    <button
                                        type="submit"
                                        class="w-44 inline-flex items-center justify-between px-4 py-3 rounded-2xl border transition
                                            {{ $isFull ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200' }}"
                                    >
                                        <span class="text-[10px] font-black uppercase {{ $isFull ? 'text-red-700' : 'text-blue-700' }}">{{ $isFull ? 'Penuh' : 'Tersedia' }}</span>
                                        <span class="text-[12px] font-black {{ $isFull ? 'text-red-600' : 'text-blue-600' }}">{{ $isFull ? 'Tandai Tersedia' : 'Tandai Penuh' }}</span>
                                    </button>
                                </form>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

