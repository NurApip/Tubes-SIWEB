

<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('active_menu','dashboard'); ?>

<?php $__env->startSection('admin_content'); ?>
    <div class="mb-10">
        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Ringkasan Sistem</p>
        <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Dashboard Admin</h2>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Uang Masuk</p>
            <h3 class="text-2xl font-black mt-2 text-slate-800">Rp <?php echo e(number_format($totalUang)); ?></h3>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Member</p>
            <h3 class="text-2xl font-black mt-2 text-slate-800"><?php echo e($totalMember); ?> User</h3>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Booking</p>
            <h3 class="text-2xl font-black mt-2 text-slate-800"><?php echo e($totalSesi); ?> Sesi</h3>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
        <h3 class="font-black uppercase mb-4 text-slate-700 text-sm tracking-wide">Daftar Pengguna Sistem</h3>
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-slate-400 uppercase text-[10px] tracking-widest border-b">
                    <th class="pb-4">Nama</th>
                    <th class="pb-4">Email/HP</th>
                    <th class="pb-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b last:border-0">
                    <td class="py-4 font-bold text-slate-800"><?php echo e($user->name); ?></td>
                    <td class="py-4 text-slate-600"><?php echo e($user->email ?? $user->hp); ?></td>
                    <td class="py-4">
                        <span class="<?php echo e($user->role == 1 ? 'text-blue-600 font-black' : 'text-slate-500 font-bold'); ?> text-xs uppercase">
                            <?php echo e($user->role == 1 ? 'Admin' : 'Member'); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FutsalHub\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>