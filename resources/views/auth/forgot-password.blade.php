<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Reset Password</h1>
        <p class="text-gray-500 mb-6">Masukkan nomor HP akun kamu untuk reset password.</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm border border-red-200 font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-3 rounded-lg mb-6 text-sm border border-green-200 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.forgot.submit') }}" class="space-y-4">
            @csrf
            <input type="text" name="user" value="{{ old('user') }}" placeholder="Masukkan no HP"
                   class="w-full border p-4 rounded-xl focus:ring-2 focus:ring-green-600 outline-none">

            <button class="w-full bg-green-700 text-white p-4 rounded-xl font-bold">
                [ LANJUTKAN ]
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-green-700 font-bold underline">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>
