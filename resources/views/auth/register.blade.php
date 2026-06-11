<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - FutsalHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Tambahan untuk Background Image dan Overlay */
        .bg-soccer-player {
            /* Ganti URL di bawah ini dengan URL gambar Google yang kamu inginkan */
            background-image: url('https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=1000&auto=format&fit=crop'); 
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Overlay Hitam Transparan agar Teks Jelas */
        .bg-soccer-player::before {
            content: "";
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Hitam dengan transparansi 50% */
            z-index: 1;
        }

        /* Memastikan teks berada di atas overlay */
        .bg-soccer-player .text-content {
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex">

    <div class="w-1/2 bg-soccer-player flex items-center justify-center p-10">
        <div class="text-center text-content">
            <h2 class="text-5xl font-bold text-white drop-shadow-lg">OWN THE PITCH...</h2>
            <p class="mt-4 text-gray-200 drop-shadow-md">Jadilah bagian dari komunitas futsal terbesar.</p>
        </div>
    </div>

    <div class="w-1/2 bg-white p-12 flex flex-col justify-center">

        <h1 class="text-3xl font-bold mb-8">DAFTAR FUTSALHUB</h1>

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full border p-3 rounded">
            <input type="email" name="email" placeholder="Email" required class="w-full border p-3 rounded">
            <input type="text" name="hp" placeholder="No. HP" required class="w-full border p-3 rounded">
            
            <div class="relative">
                <input type="password" id="password" name="password" placeholder="Kata Sandi" required class="w-full border p-3 rounded">
                <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-3.5 text-sm text-gray-500 hover:text-gray-800 font-bold">
                    Lihat
                </button>
            </div>
            
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required class="w-full border p-3 rounded">
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-3.5 text-sm text-gray-500 hover:text-gray-800 font-bold">
                    Lihat
                </button>
            </div>

            <label class="flex items-center space-x-2 p-1">
                <input type="checkbox" required class="rounded border-gray-300 text-green-600 focus:ring-green-500"> 
                <span class="text-sm">Setuju dengan 
                    <a href="#" class="text-blue-600 underline" onclick="alert('Syarat & Ketentuan: Pengguna wajib menjaga kerahasiaan akun dan mematuhi aturan pemesanan lapangan.')">
                        Syarat & Ketentuan
                    </a>
                </span>
            </label>

            <button type="submit" class="w-full bg-green-700 text-white p-3 rounded font-bold hover:bg-green-800 transition">
                BUAT AKUN
            </button>
            
            <a href="/login" class="block w-full border p-3 rounded text-center font-bold text-gray-700 hover:bg-gray-100 transition">
                LOGIN AKUN
            </a>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>