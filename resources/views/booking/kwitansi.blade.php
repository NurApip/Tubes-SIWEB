<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Kwitansi #{{ $booking->id }}</title>
</head>
<body class="bg-white p-10" onload="window.print()">
    <div class="max-w-2xl mx-auto border-2 border-gray-100 p-8 rounded-3xl">
        <div class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-black text-blue-600">FUTSALHUB</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bukti Pembayaran Sah</p>
            </div>
            <div class="text-right">
                <p class="font-black uppercase italic">Invoice #{{ $booking->id }}</p>
                <p class="text-xs text-gray-500">{{ $booking->created_at->format('d M Y H:i') }}</p>
                @if($booking->kode_tiket)
                    <p class="text-xs text-gray-700 font-black mt-2">Kode Tiket: <span class="text-blue-600">{{ $booking->kode_tiket }}</span></p>
                @endif
            </div>
        </div>

        <div class="mb-8">
            <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Penyewa:</p>
            <p class="font-bold text-lg">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="text-left py-4 text-[10px] font-black uppercase">Item</th>
                    <th class="text-right py-4 text-[10px] font-black uppercase">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-50">
                    <td class="py-4">
                        <p class="font-bold uppercase text-sm">{{ $booking->nama_gor }}</p>
                        <p class="text-[10px] text-gray-400 font-bold">{{ $booking->tgl_main }} | {{ $booking->jam_mulai }} WIB</p>
                        <p class="text-[10px] text-gray-500 font-bold">Durasi Bermain: {{ $booking->durasi_bermain ?? $booking->durasi }} jam</p>
                    </td>
                    <td class="py-4 text-right font-black text-sm">
                        Rp {{ number_format($booking->total_harga) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="bg-gray-50 p-6 rounded-2xl flex justify-between items-center">
            <span class="font-black uppercase italic text-sm">Status</span>
            <span class="bg-green-500 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">LUNAS</span>
        </div>

        <p class="text-center text-[9px] text-gray-300 font-bold uppercase mt-10 tracking-[0.3em]">
            Terima kasih telah berolahraga bersama kami
        </p>
    </div>
</body>
</html>