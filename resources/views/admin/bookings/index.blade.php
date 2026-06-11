@extends('layouts.admin')

@section('title', 'Booking - Admin')

@section('active_menu','bookings')

@section('admin_content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Manajemen Booking</p>
                <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Daftar Pesanan</h2>
            </div>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">Kelola konfirmasi dan status booking pengguna</p>
    </div>

    <div class="overflow-x-auto bg-white rounded-[32px] shadow-sm border border-slate-200">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">User</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Lapangan</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Bukti TF</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Tanggal</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Jam</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Durasi Bermain</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Kode Tiket</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($bookings->count() === 0)
                    <tr>
                        <td colspan="9" class="py-20 text-center text-gray-300 font-black uppercase text-xs italic tracking-widest">Belum ada booking.</td>
                    </tr>
                @else
                    @php
                        $grouped = $bookings->groupBy(function($b) { return $b->user_id ?? ('guest_'.$b->id); });
                    @endphp

                    @foreach($grouped as $userId => $group)
                        @php
                            $first = $group->first();
                            $userName = $first->user->name ?? ($first->nama_penyewa ?? 'Guest');
                            $userPhone = $first->user->no_hp ?? $first->user->hp ?? ($first->nomor_wa ?? '-');
                        @endphp
                        <tr class="bg-slate-50">
                            <td class="px-6 py-4 font-black text-slate-700">{{ $userName }}<div class="text-[10px] text-slate-500 font-medium mt-1">{{ $userPhone }}</div></td>
                            <td colspan="8" class="px-6 py-4"></td>
                        </tr>

                        @foreach($group as $booking)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/30">
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4 text-sm text-slate-600 uppercase font-black">{{ $booking->nama_gor }}</td>
                                <td class="px-6 py-4">
                                    @if($booking->bukti_bayar)
                                        <a href="{{ asset('storage/' . $booking->bukti_bayar) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-black">Lihat Bukti</a>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-black">Belum Upload</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $booking->tgl_main }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $booking->jam_mulai }} WIB</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $booking->durasi_bermain ?? $booking->durasi }} jam</td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    @if($booking->status === 'Success' && $booking->kode_tiket)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 text-green-700 text-sm font-black">{{ $booking->kode_tiket }}</span>
                                    @elseif($booking->status === 'Pending')
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-sm font-black">Menunggu...</span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-sm font-black">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-2 rounded-full text-[10px] font-black uppercase {{ $booking->status === 'Success' ? 'bg-green-50 text-green-700' : ($booking->status === 'Pending' ? 'bg-orange-50 text-orange-700' : 'bg-red-50 text-red-700') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-2xl bg-blue-600 text-white hover:bg-blue-700 transition">Detail</a>
                                    <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus booking ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-2xl bg-red-600 text-white hover:bg-red-700 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
