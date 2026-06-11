<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-2 flex items-center gap-2">
            🛡️ VERIFIKASI AKUN
        </h2>
        <p class="text-sm text-gray-500 mb-6">"Masukkan kode OTP yang dikirim ke no HP."</p>

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl mb-6 text-sm font-bold text-center">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-6 text-sm font-bold text-center">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('verify.otp')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <div class="flex justify-between gap-2">
                <?php for($i=1; $i<=6; $i++): ?>
                    <input type="text" name="otp_digit_<?php echo e($i); ?>" maxlength="1" 
                           class="w-12 h-14 border-2 border-gray-200 rounded-xl text-center text-2xl font-bold focus:border-green-600 focus:outline-none"
                           oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length) this.nextElementSibling?.focus()">
                <?php endfor; ?>
            </div>
            
            <input type="hidden" name="otp" id="full-otp">

            <button type="submit" onclick="combineOtp()" class="w-full bg-black text-white py-4 rounded-xl font-bold hover:bg-gray-800 transition">
                [ VERIFIKASI ]
            </button>
        </form>

        <form action="<?php echo e(route('resend.otp')); ?>" method="POST" class="text-center text-sm mt-6 text-gray-600">
            <?php echo csrf_field(); ?>
            Tidak terima kode?
            <button type="submit" class="font-bold underline text-green-700">
                [ Kirim Ulang ]
            </button>
        </form>
    </div>

    <script>
        function combineOtp() {
            let otp = "";
            document.querySelectorAll('input[name^="otp_digit_"]').forEach(input => {
                otp += input.value;
            });
            document.getElementById('full-otp').value = otp;
        }
    </script>

    <script>
    // Fungsi agar kursor otomatis loncat ke kotak berikutnya
    const inputs = document.querySelectorAll('input[name^="otp_digit_"]');
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        
        // Agar bisa hapus (backspace) ke kotak sebelumnya
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
    
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\FutsalHub\resources\views/auth/verifikasi.blade.php ENDPATH**/ ?>