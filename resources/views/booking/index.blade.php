@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
@php
    $membershipStatus = Auth::user()->membershipLabel();
    $membershipClass = Auth::user()->isActiveMember()
        ? 'text-green-600'
        : (Auth::user()->isPendingMember()
            ? 'text-yellow-600'
            : (Auth::user()->isExpiredMember() ? 'text-red-600' : 'text-orange-600'));
@endphp

<div class="w-full">
    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-blue-900/5 p-10 flex items-center gap-10 relative overflow-hidden mb-10 border border-gray-100">
    
    <div class="relative flex-shrink-0">
        <div class="w-32 h-32 rounded-full border-4 border-dashed border-blue-100 p-1 animate-[spin_20s_linear_infinite]"></div>
        <div class="absolute inset-1 w-30 h-30 bg-blue-600 rounded-full flex items-center justify-center text-white text-5xl font-black uppercase overflow-hidden shadow-inner border-4 border-white">
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="w-full h-full object-cover">
            @else
                {{ substr(Auth::user()->name, 0, 1) }}
            @endif
        </div>
    </div>

    <div class="relative z-10 flex-grow">
        
        
        <h2 class="text-5xl font-black text-gray-800 uppercase italic tracking-tighter leading-none mb-4">
            {{ Auth::user()->name }}
        </h2>
        
        <div class="flex items-center gap-6">
            <div class="bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                <p class="text-[8px] font-black text-gray-400 uppercase mb-1">Status Keanggotaan</p>
                <span class="{{ $membershipClass }} text-xs font-black uppercase italic tracking-widest">[ {{ $membershipStatus }} ]</span>
            </div>

            <div class="bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                <p class="text-[8px] font-black text-gray-400 uppercase mb-1">Total Booking</p>
                <span class="text-gray-800 text-xs font-black uppercase tracking-widest">{{ $bookings->count() }} </span>
            </div>
        </div>
    </div>

    <i class="fas fa-id-badge absolute -right-8 -bottom-8 text-blue-600 opacity-[0.03] text-[18rem] transform -rotate-12 pointer-events-none"></i>
    
    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100%] opacity-50"></div>
</div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-10">
        <div class="flex items-center gap-3 mb-8">
            <i class="fas fa-ticket-alt text-blue-600"></i>
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight">Booking History</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b-2">
                        <th class="pb-4">ID</th>
                        <th class="pb-4">GOR / Lapangan</th>
                        <th class="pb-4">Tanggal</th>
                        <th class="pb-4">Durasi Bermain</th>
                        <th class="pb-4">Total</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4">Kode Tiket</th>
                        <th class="pb-4">Rating & Review</th>
                    </tr>
                </thead>
                <tbody>
    @forelse($bookings as $b)
    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
        <td class="py-6 font-bold text-gray-800 italic uppercase text-xs">{{ $b->id }}</td>
        <td class="py-6 font-bold text-gray-800 italic uppercase text-xs">{{ $b->nama_gor }}</td>
        <td class="py-6 text-xs font-medium text-gray-500">{{ $b->tgl_main }}</td>
        <td class="py-6 text-xs font-medium text-gray-500">{{ $b->durasi_bermain ?? $b->durasi }} jam</td>
        <td class="py-6">
            <p class="text-xs font-black text-blue-600 tracking-tighter">Rp {{ number_format($b->total_harga) }}</p>
        </td>
        <td class="py-6">
            <div class="flex flex-col gap-3">
                <span class="w-fit px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $b->status == 'Pending' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600' }}">
                    [ {{ $b->status }} ]
                </span>

                @if($b->status == 'Pending' && !$b->bukti_bayar)
                    <form action="{{ route('booking.upload', $b->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="bukti_bayar" class="text-[9px] font-bold text-gray-400 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:bg-blue-50 file:text-blue-700 cursor-pointer" required>
                        <button type="submit" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 shadow-md transition">
                            <i class="fas fa-upload text-[10px]"></i>
                        </button>
                    </form>
                @elseif($b->bukti_bayar && $b->status == 'Pending')
                    <span class="text-[9px] font-black text-blue-500 italic uppercase tracking-tighter">
                        <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi Admin
                    </span>
                @endif

              @if($b->status == 'Success')
    <a href="{{ route('booking.kwitansi', $b->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-gray-800 text-white text-[9px] font-black px-4 py-2 rounded-xl uppercase hover:bg-black transition shadow-lg">
        <i class="fas fa-file-invoice"></i> Cetak Kwitansi
    </a>
@endif
            </div>
        </td>
        <td class="py-6 text-xs font-black text-gray-700">
            @if($b->status == 'Success' && $b->kode_tiket)
                <span class="text-[11px] font-black text-green-600">{{ $b->kode_tiket }}</span>
            @elseif($b->status == 'Pending')
                <span class="text-[11px] font-black text-gray-400 italic">Menunggu...</span>
            @else
                <span class="text-[11px] font-black text-gray-300">-</span>
            @endif
        </td>
        <td class="py-6 min-w-[260px]">
            @if($b->review)
                <div class="rounded-2xl border border-yellow-100 bg-yellow-50 p-4">
                    <div class="flex items-center gap-1 text-yellow-400">
                        @for($star = 1; $star <= 5; $star++)
                            <i class="{{ $star <= $b->review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                        <span class="ml-2 text-xs font-black text-gray-700">{{ $b->review->rating }}/5</span>
                    </div>
                    @if($b->review->comment)
                        <p class="mt-2 text-[10px] font-medium leading-relaxed text-gray-500">{{ $b->review->comment }}</p>
                    @endif
                </div>
            @elseif($b->status === 'Success' && $b->lapangan_id)
                <form action="{{ route('booking.review.store', $b->id) }}" method="POST" class="space-y-2 rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    @csrf
                    <select name="rating" required class="w-full rounded-xl border border-gray-200 bg-white p-2 text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="">Pilih Rating</option>
                        <option value="5">5 - Sangat Puas</option>
                        <option value="4">4 - Puas</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Buruk</option>
                    </select>
                    <textarea name="comment" maxlength="1000" rows="2" placeholder="Tulis review (opsional)" class="w-full resize-none rounded-xl border border-gray-200 bg-white p-2 text-[10px] text-gray-700 outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                    <button type="submit" class="w-full rounded-xl bg-yellow-400 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-yellow-950 transition hover:bg-yellow-500">
                        Kirim Review
                    </button>
                </form>
            @else
                <span class="text-[10px] font-bold italic text-gray-300">Belum tersedia</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="py-20 text-center text-gray-300 font-black uppercase text-xs italic tracking-widest">
            Belum ada aktivitas penyewaan
        </td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>

        <div class="mt-10 p-4 border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50">
            <p class="text-[9px] text-gray-400 font-bold italic text-center uppercase tracking-widest">
                *Tombol Unduh Kwitansi hanya muncul jika status sudah LUNAS. Mohon hubungi admin jika terdapat kendala verifikasi.
            </p>
        </div>
    </div>
</div>
@endsection
