

<?php $__env->startSection('title', 'Akun Member - Admin'); ?>

<?php $__env->startSection('active_menu','members'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="mb-10">
    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Manajemen Membership</p>
    <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Verifikasi Akun Member</h2>
</div>

<?php if(session('success')): ?>
    <div class="mb-6 bg-green-50 text-green-700 border border-green-200 p-4 rounded-2xl text-sm font-black">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="text-slate-400 uppercase text-[10px] tracking-widest border-b">
                <th class="pb-4">Nama Pelanggan</th>
                <th class="pb-4">Email / Kontak HP</th>
                <th class="pb-4">Status</th>
                <th class="pb-4">Bukti Transfer</th>
                <th class="pb-4">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b last:border-0 hover:bg-slate-50/50 transition">
                <td class="py-4 font-bold text-slate-800">
                    <?php echo e($user->name); ?>

                </td>

                <td class="py-4 text-slate-600">
                    <?php echo e($user->email ?? $user->hp); ?>

                </td>

                <td class="py-4">
                    <?php if($user->isActiveMember()): ?>
                        <span class="bg-green-50 text-green-700 text-[10px] px-3 py-1.5 rounded-xl font-black uppercase tracking-wider border border-green-100">
                            Member Aktif
                        </span>
                    <?php elseif($user->isPendingMember()): ?>
                        <span class="bg-yellow-50 text-yellow-700 text-[10px] px-3 py-1.5 rounded-xl font-black uppercase tracking-wider border border-yellow-100">
                            Pending
                        </span>
                    <?php else: ?>
                        <span class="bg-gray-50 text-gray-600 text-[10px] px-3 py-1.5 rounded-xl font-black uppercase tracking-wider border border-gray-100">
                            Regular
                        </span>
                    <?php endif; ?>
                </td>

                <td class="py-4">
                    <?php if($user->membership_bukti): ?>
                        <a href="<?php echo e(asset('storage/' . $user->membership_bukti)); ?>" target="_blank"
                           class="text-blue-600 font-black underline text-[10px] uppercase">
                            Lihat Bukti
                        </a>
                    <?php else: ?>
                        <span class="text-slate-400 text-[10px] font-bold uppercase">Belum Ada</span>
                    <?php endif; ?>
                </td>

                <td class="py-4">
                    <div class="flex gap-2">
                        <?php if($user->isPendingMember()): ?>
                            <form action="<?php echo e(route('admin.members.update', $user->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="is_member" value="1">
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase">
                                    Approve
                                </button>
                            </form>

                            <form action="<?php echo e(route('admin.members.update', $user->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="is_member" value="0">
                                <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase">
                                    Reject
                                </button>
                            </form>
                        <?php elseif($user->isActiveMember()): ?>
                            <form action="<?php echo e(route('admin.members.update', $user->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="is_member" value="0">
                                <button type="submit"
                                    class="bg-slate-800 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase">
                                    Nonaktifkan
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-slate-400 text-[10px] font-bold uppercase">Tidak ada aksi</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FutsalHub\resources\views/admin/members.blade.php ENDPATH**/ ?>