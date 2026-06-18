<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Reset Password</h1>
        <p class="text-gray-500 mb-6">Masukkan nomor HP akun kamu untuk reset password.</p>

        <?php if($errors->any()): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm border border-red-200 font-bold">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="bg-green-50 text-green-700 p-3 rounded-lg mb-6 text-sm border border-green-200 font-bold">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.forgot.submit')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <input type="text" name="user" value="<?php echo e(old('user')); ?>" placeholder="Masukkan no HP"
                   class="w-full border p-4 rounded-xl focus:ring-2 focus:ring-green-600 outline-none">

            <button class="w-full bg-green-700 text-white p-4 rounded-xl font-bold">
                [ LANJUTKAN ]
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="<?php echo e(route('login')); ?>" class="text-green-700 font-bold underline">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\FutsalHub\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>