<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperasionalController;
use App\Http\Controllers\ReviewController;

// Landing Page
Route::get('/', [LapanganController::class, 'index']);

// Public field pages
Route::get('/dashboard', [LapanganController::class, 'index'])->name('dashboard');
Route::get('/lapangan/{id}', [LapanganController::class, 'show'])->name('lapangan.show');
Route::view('/cara-booking', 'booking.guide')->name('booking.guide');

// Reset password via OTP WhatsApp
Route::get('/forgot-password', [LoginController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [LoginController::class, 'sendPasswordResetOtp'])->name('password.forgot.submit');
Route::get('/reset-password/verify', [LoginController::class, 'showPasswordResetOtpForm'])->name('password.reset.verify.form');
Route::post('/reset-password/verify', [LoginController::class, 'verifyPasswordResetOtp'])->name('password.reset.verify');
Route::post('/reset-password/resend', [LoginController::class, 'resendPasswordResetOtp'])->name('password.reset.resend');
Route::get('/reset-password', [LoginController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.reset.submit');

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/verifikasi', function () {
        if (!session()->has('otp')) {
            return redirect()->route('login');
        }

        return view('auth.verifikasi');
    })->name('verifikasi');

    Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [LoginController::class, 'resendOtp'])->name('resend.otp');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('/lapangan/{id}/favorite', [LapanganController::class, 'toggleFavorite'])->name('lapangan.favorite');
    Route::get('/gor-favorit', [LapanganController::class, 'favorites'])->name('lapangan.favorites');

    Route::get('/membership', function () {
        return view('member.index');
    })->name('membership.index');

    Route::post('/membership/join', [BookingController::class, 'joinMember'])->name('membership.join');
    Route::get('/profil', [AuthController::class, 'showProfile'])->name('profil.edit');
    Route::post('/profil', [AuthController::class, 'updateProfile'])->name('profil.update');

    Route::get('/booking/konfirmasi/{id}', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/{id}/upload', [BookingController::class, 'uploadBukti'])->name('booking.upload');
    Route::get('/riwayat-booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/kwitansi/{id}', [BookingController::class, 'kwitansi'])->name('booking.kwitansi');
    Route::post('/booking/{id}/review', [ReviewController::class, 'store'])->name('booking.review.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/members', [AdminController::class, 'members'])->name('admin.members');
    Route::post('/members/{id}/update', [AdminController::class, 'memberUpdate'])
    ->name('admin.members.update');
    Route::get('/pendapatan', [AdminController::class, 'pendapatan'])->name('admin.pendapatan');

    Route::get('/fields', [LapanganController::class, 'adminIndex'])->name('admin.fields');
    Route::post('/fields/store', [LapanganController::class, 'adminStore'])->name('admin.fields.store');
    Route::get('/fields/{id}/edit', [LapanganController::class, 'adminEdit'])->name('admin.fields.edit');
    Route::put('/fields/{id}/update', [LapanganController::class, 'adminUpdate'])->name('admin.fields.update');
    Route::patch('/fields/{id}/status', [LapanganController::class, 'adminToggleStatus'])->name('admin.fields.status');
    Route::delete('/fields/{lapanganId}/gallery/{fotoId}/delete', [LapanganController::class, 'adminGalleryDestroy'])->name('admin.fields.gallery.delete');

    Route::get('/operasional', [OperasionalController::class, 'index'])->name('admin.operasional');
    Route::post('/operasional/{lapanganId}/update', [OperasionalController::class, 'update'])->name('admin.operasional.update');

    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::get('/bookings/{id}', [AdminController::class, 'bookingDetail'])->name('admin.bookings.show');
    Route::post('/bookings/{id}/update', [AdminController::class, 'bookingUpdate'])->name('admin.bookings.update');
    Route::delete('/bookings/{id}/delete', [AdminController::class, 'bookingDestroy'])->name('admin.bookings.delete');
});

// Logout
Route::middleware(['auth'])->post('/logout', [LoginController::class, 'logout'])->name('logout');
