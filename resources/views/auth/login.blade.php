<!DOCTYPE html>
<html lang="id">

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex bg-gray-100">
    <div class="w-1/2 bg-gray-900 flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-green-900 opacity-60"></div>
        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-white text-center p-12">
            <h2 class="text-5xl font-bold mb-4">WELCOME BACK</h2>
            <p class="text-xl">Siapkan strategimu, lapangan sudah menunggu.</p>
        </div>
    </div>

    <div class="w-1/2 flex items-center justify-center p-12 bg-white">
        <div class="w-full max-w-sm">
            <h1 id="login-title" class="text-3xl font-bold mb-8 text-gray-800">LOGIN FUTSALHUB</h1>

            @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm border border-red-200 text-center font-bold">
                {{ $errors->first() }}
            </div>
            @endif

            <form id="login-form" action="/login" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="login_mode" id="login-mode" value="email">

                <input type="text" id="user-input" name="user" placeholder="Username / Email" required class="w-full border p-4 rounded-xl focus:ring-2 focus:ring-green-600 outline-none">

                <div id="password-wrapper" class="relative">
                    <input type="password" id="password-input" name="password" placeholder="Password" class="w-full border p-4 rounded-xl focus:ring-2 focus:ring-green-600 outline-none">
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-4 text-xs text-gray-400 font-bold hover:text-gray-800">Lihat</button>
                </div>

                <button type="submit" id="main-btn" class="w-full bg-green-700 text-white p-4 rounded-xl font-bold hover:bg-green-800 transition duration-300 shadow-lg">[ LOGIN ]</button>
                <div class="text-right">
                    <a href="{{ route('password.forgot') }}" class="text-sm text-green-700 font-bold underline">
                        Lupa Password?
                    </a>
                </div>
            </form>

            <div class="my-8 flex items-center gap-4">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-gray-400 text-sm">ATAU</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <button id="toggle-btn" onclick="toggleMode()" class="w-full border-2 border-gray-200 p-4 rounded-xl font-bold hover:bg-gray-50 transition">
                [ LOGIN VIA NO HP ]
            </button>

            <p class="mt-8 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="/register" class="text-green-700 underline font-bold">Daftar sekarang</a>
            </p>
        </div>
    </div>

    @php
    $isPhoneMode = old('login_mode') === 'phone' ||
    ($errors->any() && str_contains($errors->first(), 'HP'));
    @endphp

    <script>
        let isPhoneMode = @json($isPhoneMode);

        function togglePassword() {
            const passInp = document.getElementById('password-input');
            passInp.type = (passInp.type === "password") ? "text" : "password";
        }

        function updateUI() {
            const title = document.getElementById('login-title');
            const loginMode = document.getElementById('login-mode');
            const userInp = document.getElementById('user-input');
            const passWrap = document.getElementById('password-wrapper');
            const passInp = document.getElementById('password-input');
            const toggleBtn = document.getElementById('toggle-btn');

            if (isPhoneMode) {
                loginMode.value = "phone";
                title.innerText = "LOGIN VIA NO HP";
                userInp.placeholder = "Masukkan No. HP";
                passWrap.classList.add('hidden');
                passInp.disabled = true;
                passInp.removeAttribute('required');
                toggleBtn.innerText = "[ KEMBALI KE LOGIN EMAIL ]";
            } else {
                loginMode.value = "email";
                title.innerText = "LOGIN FUTSALHUB";
                userInp.placeholder = "Username / Email";
                passWrap.classList.remove('hidden');
                passInp.disabled = false;
                passInp.setAttribute('required', 'required');
                toggleBtn.innerText = "[ LOGIN VIA NO HP ]";
            }
        }

        function toggleMode() {
            isPhoneMode = !isPhoneMode;
            updateUI();
            document.getElementById('user-input').value = "";
        }

        window.onload = updateUI;
    </script>
</body>

</html>
