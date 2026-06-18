# FutsalHub

FutsalHub adalah aplikasi berbasis web untuk membantu pengguna mencari dan memesan lapangan futsal secara daring. Sistem menyediakan informasi lapangan, pemeriksaan jadwal, pemesanan, pembayaran, membership, serta pengelolaan operasional melalui dashboard admin.

Proyek ini dibuat untuk memenuhi tugas besar mata kuliah Sistem Informasi Berbasis Web (SiWeb).

## Fitur Utama dan Hak Akses

Sistem memiliki dua jenis pengguna dengan hak akses yang dipisahkan menggunakan middleware.

### 1. Admin

- **Dashboard:** Menampilkan jumlah pengguna, jumlah booking, dan total pendapatan.
- **Manajemen Lapangan:** Menambah, melihat, memperbarui, dan menghapus data lapangan.
- **Galeri Lapangan:** Mengunggah foto utama dan beberapa foto galeri.
- **Status Operasional:** Mengatur ketersediaan slot Pagi, Siang, Sore, dan Malam.
- **Manajemen Booking:** Melihat detail booking dan mengubah status menjadi `Pending`, `Success`, atau `Cancelled`.
- **Kode Tiket:** Membuat kode tiket unik secara otomatis ketika booking disetujui.
- **Notifikasi WhatsApp:** Mengirim informasi booking kepada pengguna setelah pembayaran diverifikasi.
- **Manajemen Membership:** Menyetujui, menolak, atau mengatur status membership pengguna.
- **Laporan Pendapatan:** Menampilkan data booking dan total pendapatan.

### 2. Pengguna

- **Registrasi dan Login:** Pengguna dapat membuat akun dan masuk menggunakan email atau username.
- **Login OTP WhatsApp:** Pengguna dapat masuk menggunakan nomor HP dan kode OTP enam digit.
- **Reset Password:** Pemulihan password dilakukan melalui verifikasi OTP WhatsApp.
- **Pencarian Lapangan:** Lapangan dapat difilter berdasarkan area dan tipe rumput.
- **Detail Lapangan:** Menampilkan harga, fasilitas, galeri, lokasi, dan status operasional.
- **Booking Lapangan:** Memilih tanggal, jam mulai, dan durasi bermain.
- **Pemeriksaan Jadwal:** Sistem mencegah booking pada lapangan dan waktu yang saling bertabrakan.
- **Pembayaran:** Pengguna dapat mengunggah bukti pembayaran.
- **Riwayat dan Kwitansi:** Menampilkan riwayat booking dan kwitansi untuk booking berstatus berhasil.
- **Membership:** Pengguna dapat mengajukan membership dengan mengunggah bukti pembayaran.
- **Diskon Member:** Member aktif memperoleh diskon 10% dari harga booking.
- **Profil:** Pengguna dapat memperbarui nama, email, nomor HP, dan foto profil.

## Alur Utama Sistem

### Booking Lapangan

1. Pengguna memilih lapangan dan jadwal bermain.
2. Sistem memeriksa status aktif lapangan dan ketersediaan slot.
3. Sistem memeriksa kemungkinan bentrok dengan booking berstatus `Pending` atau `Success`.
4. Total harga dihitung berdasarkan harga per jam dan durasi.
5. Diskon 10% diterapkan jika pengguna merupakan member aktif.
6. Booking disimpan dengan status awal `Pending`.
7. Setelah pembayaran diverifikasi admin, status berubah menjadi `Success`, kode tiket dibuat, dan notifikasi WhatsApp dikirim.

### Login dan Reset Password dengan OTP

1. Sistem membuat OTP acak sebanyak enam digit.
2. OTP dikirim ke nomor WhatsApp pengguna melalui Fonnte.
3. OTP login disimpan sementara di session dan berlaku selama lima menit.
4. Pengiriman ulang OTP dibatasi dengan jeda 60 detik.
5. OTP reset password disimpan dalam bentuk hash pada tabel `password_reset_tokens`.

## Teknologi

- **Backend:** Laravel 12 dan PHP 8.2 atau lebih baru
- **Frontend:** Blade Template, Tailwind CSS, Font Awesome, dan Vanilla JavaScript
- **Database:** MySQL
- **ORM:** Laravel Eloquent
- **Build Tool:** Vite
- **Integrasi Pihak Ketiga:** Fonnte WhatsApp Gateway
- **Pengujian:** PHPUnit

## Struktur Data Utama

- **Users:** Menyimpan akun, role, profil, dan status membership.
- **Lapangan:** Menyimpan informasi lapangan, harga, lokasi, fasilitas, dan status aktif.
- **Foto Lapangan:** Menyimpan galeri foto yang terhubung dengan lapangan.
- **Bookings:** Menyimpan jadwal, penyewa, pembayaran, status, harga, dan kode tiket.
- **Operasional Lapangan:** Menyimpan status penuh atau tersedia untuk setiap slot lapangan.

Relasi utama sistem adalah pengguna memiliki booking, booking terhubung dengan lapangan, dan lapangan memiliki banyak foto galeri serta status operasional.

## Panduan Instalasi

### 1. Persiapan

Pastikan perangkat telah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan NPM
- MySQL
- Web server seperti XAMPP atau Laragon

Salin repositori dan masuk ke direktori proyek:

```bash
git clone https://github.com/NurApip/Tubes-SIWEB.git
cd Tubes-SIWEB
```

### 2. Instal Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file konfigurasi:

```bash
cp .env.example .env
```

Pada Windows Command Prompt dapat menggunakan:

```bat
copy .env.example .env
```

Sesuaikan nama aplikasi, URL, dan koneksi MySQL di dalam `.env`:

```env
APP_NAME=FutsalHub
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=futsalhub
DB_USERNAME=root
DB_PASSWORD=
```

Buat application key:

```bash
php artisan key:generate
```

### 4. Migrasi dan Seeder

Buat database bernama `futsalhub`, kemudian jalankan:

```bash
php artisan migrate:fresh --seed
```

Seeder utama akan membuat akun admin bawaan. Data lapangan dapat ditambahkan melalui dashboard admin.

### 5. Storage Link

Buat symbolic link agar foto profil, lapangan, bukti pembayaran, dan bukti membership dapat diakses oleh browser:

```bash
php artisan storage:link
```

### 6. Konfigurasi WhatsApp Gateway

Fitur OTP dan notifikasi booking menggunakan API Fonnte. Siapkan token Fonnte yang aktif untuk fungsi pengiriman WhatsApp pada:

- `app/Http/Controllers/LoginController.php`
- `app/Http/Controllers/BookingController.php`

Nomor WhatsApp pengguna harus valid dan dapat diubah otomatis dari awalan `08` menjadi format negara `62`.

### 7. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite pada terminal lain:

```bash
npm run dev
```

Aplikasi dapat dibuka melalui `http://127.0.0.1:8000`.

## Akun Uji Coba

Akun admin berikut dibuat oleh `DatabaseSeeder`:

| Peran | Email | Password |
|---|---|---|
| Admin | `adminfutsal@gmail.com` | `Admin123` |

Akun pengguna dapat dibuat melalui halaman registrasi.

## Perintah Pengujian

```bash
php artisan test
```

## Catatan Keamanan

- Password pengguna disimpan menggunakan hash.
- Form menggunakan perlindungan CSRF Laravel.
- Halaman pengguna dan admin dilindungi middleware autentikasi dan role.
- File gambar divalidasi berdasarkan tipe dan ukuran.
- Untuk penggunaan publik, simpan token layanan pihak ketiga di environment variable dan jangan memasukkannya langsung ke repository.

## Lisensi

Proyek ini menggunakan lisensi MIT sesuai konfigurasi proyek Laravel.
