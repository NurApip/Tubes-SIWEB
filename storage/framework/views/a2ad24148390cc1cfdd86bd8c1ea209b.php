<?php $__env->startSection('title', 'Dashboard User'); ?>

<?php $__env->startSection('content'); ?>
<aside class="w-1/4">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
        <h2 class="font-black text-gray-800 mb-6 flex items-center gap-2 tracking-wide text-sm">
            <i class="fas fa-search text-blue-600"></i> FILTER PENCARIAN
        </h2>
        
        <form action="<?php echo e(route('dashboard')); ?>" method="GET">
            <div class="mb-5">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">PILIH AREA</label>
                <select name="area" class="w-full border border-gray-200 p-3 rounded-xl bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Area</option>
                    <option value="Bandung Pusat">Bandung Pusat</option>
                    <option value="Bandung Timur">Bandung Timur</option>
                    <option value="Bandung Tengah">Bandung Tengah</option>
                    <option value="Bandung Utara">Bandung Utara</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">TIPE RUMPUT</label>
                <select name="tipe_rumput" class="w-full border border-gray-200 p-3 rounded-xl bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Tipe</option>
                    <option value="Sintetis">Rumput Sintetis</option>
                    <option value="Interlock">Interlock</option>
                    <option value="Mat">Vinyl / Mat</option>
                    <option value="Basah">Rumput Alami</option>
                </select>
            </div>
            
            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-black py-4 rounded-xl transition shadow-lg shadow-blue-100 flex items-center justify-center gap-2 text-xs tracking-widest uppercase">
                Apply Filter
            </button>
        </form>

  
         <div class="mt-8 pt-8 border-t border-dashed">
            <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                <p class="text-[10px] font-bold text-green-700 uppercase leading-none mb-3 font-black">Benefit Member</p>

                <?php if(Auth::user()->isActiveMember()): ?>
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
                <?php elseif(Auth::user()->isPendingMember()): ?>
                    <p class="text-[10px] font-bold text-yellow-700 leading-relaxed">
                        Pengajuan membership kamu sedang menunggu verifikasi admin.
                    </p>
                <?php else: ?>
                    <p class="text-[10px] font-bold text-green-800 leading-relaxed mb-3">
                        Aktifkan membership untuk mendapatkan diskon 10% setiap booking.
                    </p>
                    <a href="<?php echo e(route('membership.index')); ?>" class="inline-flex w-full items-center justify-center bg-green-600 text-white rounded-lg py-2 text-[9px] font-black uppercase tracking-widest hover:bg-green-700 transition">
                        Daftar Member
                    </a>
                <?php endif; ?>
            </div>
        </div> 
    
    </div>
</aside>

<?php if(session('success')): ?>
<a href="<?php echo e(route('booking.index')); ?>" id="notif-booking" class="fixed top-5 right-5 z-[100] group transition-all duration-500">
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
<?php endif; ?>

<main class="w-3/4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black text-gray-800 tracking-tight italic uppercase">Rekomendasi Gor Terdekat</h1>
        <div class="flex gap-2">
            <button class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-blue-600 shadow-sm"><i class="fas fa-th-large"></i></button>
            <button class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 transition"><i class="fas fa-list"></i></button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8">
        <?php $__currentLoopData = $lapangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300">
            <div class="h-48 bg-gray-100 relative flex items-center justify-center overflow-hidden">
                <?php if($item->foto): ?>
                    <img src="<?php echo e(asset('storage/' . $item->foto)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <?php else: ?>
                    <i class="fas fa-futbol text-6xl text-gray-300 group-hover:scale-110 transition duration-500"></i>
                <?php endif; ?>
                
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full shadow-sm text-[10px] font-black text-blue-700">
                    <i class="fas fa-star mr-1"></i> 4.8
                </div>
            </div>

            <div class="p-6">
                <?php $active = (bool)($item->is_active ?? true); ?>
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-black text-xl text-gray-800 uppercase tracking-tight"><?php echo e($item->nama_lapangan); ?></h3>
                    <span class="<?php echo e($active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'); ?> text-[10px] px-2 py-1 rounded-lg font-black uppercase tracking-tighter">
                        <?php echo e($active ? 'Arena Aktif' : 'Arena Non-Aktif'); ?>

                    </span>
                </div>

                <p class="text-xs text-gray-400 uppercase font-black tracking-wider mb-2"><i class="fas fa-map-marker-alt text-blue-500"></i> <?php echo e($item->lokasi); ?></p>
                <p class="text-xs text-gray-500 mb-6 line-clamp-2 italic leading-relaxed"><?php echo e($item->fasilitas); ?></p>
                
                <div class="flex items-center justify-between border-t border-gray-50 pt-5">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Mulai Dari</p>
                        <p class="font-black text-blue-700 text-xl tracking-tighter">Rp <?php echo e(number_format($item->harga_per_jam, 0, ',', '.')); ?></p>
                    </div>
                    <a href="<?php echo e(route('lapangan.show', $item->lapangan_id)); ?>" class="inline-block bg-gray-100 group-hover:bg-blue-700 group-hover:text-white text-gray-800 px-5 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition shadow-sm text-center">
                        Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FutsalHub\resources\views/lapangan/index.blade.php ENDPATH**/ ?>