<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Verifikasi OTP Reset</h1>
        <p class="text-gray-500 mb-6">Masukkan kode OTP reset password yang dikirim ke WhatsApp kamu.</p>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-3 rounded-lg mb-6 text-sm border border-green-200 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm border border-red-200 font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset.verify') }}" class="space-y-6">
            @csrf
            <div class="flex justify-between gap-2">
                @for($i=1; $i<=6; $i++)
                    <input type="text" name="otp_digit_{{ $i }}" maxlength="1"
                        class="w-12 h-14 border-2 border-gray-200 rounded-xl text-center text-2xl font-bold focus:border-green-600 focus:outline-none"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length) this.nextElementSibling?.focus()">
                @endfor
            </div>

            <input type="hidden" name="otp" id="full-otp">

            <button type="submit" onclick="combineOtp()" class="w-full bg-green-700 text-white p-4 rounded-xl font-bold">
                [ VERIFIKASI OTP ]
            </button>
        </form>

        <form method="POST" action="{{ route('password.reset.resend') }}" class="text-center text-sm mt-6 text-gray-600">
            @csrf
            Tidak terima kode?
            <button type="submit" class="font-bold underline text-green-700">
                [ Kirim Ulang ]
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-green-700 font-bold underline">Kembali ke Login</a>
        </p>
    </div>

    <script>
        function combineOtp() {
            let otp = "";
            document.querySelectorAll('input[name^="otp_digit_"]').forEach(input => {
                otp += input.value;
            });
            document.getElementById('full-otp').value = otp;
        }

        const inputs = document.querySelectorAll('input[name^="otp_digit_"]');
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>
</html>
