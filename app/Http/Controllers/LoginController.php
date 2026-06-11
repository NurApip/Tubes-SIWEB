<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
        ]);

        // LOGIN VIA EMAIL / USERNAME
        if ($request->login_mode === 'email') {
            $request->validate([
                'password' => 'required',
            ]);

            $user = User::where('email', $request->user)
                ->orWhere('name', $request->user)
                ->first();

            if (!$user || !Auth::validate(['email' => $user->email, 'password' => $request->password])) {
                return back()->withInput()->withErrors(['msg' => 'Email atau Password salah!']);
            }

            Auth::login($user);

            if ((int) $user->role === 1) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        // LOGIN VIA NO HP -> PAKAI OTP
        $user = User::where('hp', $request->user)
            ->orWhere('no_hp', $request->user)
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors(['msg' => 'Nomor HP tidak terdaftar!']);
        }

        // Admin tidak perlu OTP via phone juga
        if ((int) $user->role === 1) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        $otp = (string) random_int(100000, 999999);

        Session::put([
            'otp' => $otp,
            'temp_user_id' => $user->id,
            'otp_expires_at' => now()->addMinutes(5)->timestamp,
            'otp_last_sent_at' => now()->timestamp,
        ]);

        $otpSent = $this->sendOtpWhatsapp($user->hp ?? $user->no_hp, $otp);

        if (!$otpSent) {
            Session::forget(['otp', 'temp_user_id', 'otp_expires_at', 'otp_last_sent_at']);

            return back()->withInput()->withErrors(['msg' => 'Gagal mengirim OTP ke WhatsApp. Silakan coba lagi.']);
        }

        return redirect()->route('verifikasi')->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    public function verifyOtp(Request $request)
    {
        $validOtp = Session::get('otp');
        $tempUserId = Session::get('temp_user_id');
        $expiresAt = Session::get('otp_expires_at');

        if (!$validOtp || !$tempUserId) {
            return redirect()->route('login')->withErrors(['msg' => 'Sesi berakhir, silakan login ulang.']);
        }

        if (!$expiresAt || now()->timestamp > $expiresAt) {
            Session::forget(['otp', 'temp_user_id', 'otp_expires_at', 'otp_last_sent_at']);

            return redirect()->route('login')->withErrors(['msg' => 'Kode OTP sudah kedaluwarsa. Silakan login ulang.']);
        }

        if ($request->otp !== $validOtp) {
            return back()->withErrors(['msg' => 'Kode OTP salah! Cek kembali.']);
        }

        Auth::loginUsingId($tempUserId);

        Session::forget(['otp', 'temp_user_id', 'otp_expires_at', 'otp_last_sent_at']);

        $user = Auth::user();

        if ((int) $user->role === 1) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function resendOtp(Request $request)
    {
        $tempUserId = Session::get('temp_user_id');
        $lastSentAt = Session::get('otp_last_sent_at');

        if (!$tempUserId) {
            return redirect()->route('login')->withErrors(['msg' => 'Sesi berakhir, silakan login ulang.']);
        }

        if ($lastSentAt && now()->timestamp - $lastSentAt < 60) {
            $remainingSeconds = 60 - (now()->timestamp - $lastSentAt);

            return back()->withErrors(['msg' => "Tunggu {$remainingSeconds} detik sebelum kirim ulang OTP."]);
        }

        $user = User::find($tempUserId);

        if (!$user) {
            Session::forget(['otp', 'temp_user_id', 'otp_expires_at', 'otp_last_sent_at']);

            return redirect()->route('login')->withErrors(['msg' => 'User tidak ditemukan, silakan login ulang.']);
        }

        $otp = (string) random_int(100000, 999999);
        $phone = $user->hp ?? $user->no_hp;
        $otpSent = $this->sendOtpWhatsapp($phone, $otp);

        if (!$otpSent) {
            return back()->withErrors(['msg' => 'Gagal mengirim ulang OTP ke WhatsApp. Coba beberapa saat lagi.']);
        }

        Session::put([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)->timestamp,
            'otp_last_sent_at' => now()->timestamp,
        ]);

        return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'user' => 'required|string|max:255',
        ]);

        $identifier = $request->user;
        $user = User::where('email', $identifier)
            ->orWhere('hp', $identifier)
            ->orWhere('no_hp', $identifier)
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors(['user' => 'Akun tidak ditemukan.']);
        }

        $otp = (string) random_int(100000, 999999);
        $phone = $user->hp ?? $user->no_hp;

        if (!$this->sendOtpWhatsapp($phone, $otp)) {
            return back()->withInput()->withErrors(['user' => 'Gagal mengirim OTP reset password ke WhatsApp. Silakan coba lagi.']);
        }

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        Session::put([
            'reset_email' => $user->email,
            'reset_otp_verified' => false,
            'reset_otp_last_sent_at' => now()->timestamp,
        ]);

        return redirect()->route('password.reset.verify.form')->with('success', 'Kode OTP reset password telah dikirim ke WhatsApp Anda.');
    }

    public function showPasswordResetOtpForm()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.forgot');
        }

        return view('auth.verify-reset-password-otp');
    }

    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = Session::get('reset_email');

        if (!$email) {
            return redirect()->route('password.forgot')->withErrors(['user' => 'Sesi reset password berakhir. Silakan mulai ulang.']);
        }

        $resetToken = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetToken) {
            return redirect()->route('password.forgot')->withErrors(['user' => 'Kode reset tidak ditemukan. Silakan mulai ulang.']);
        }

        if (now()->diffInMinutes($resetToken->created_at) >= 5) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            Session::forget(['reset_email', 'reset_otp_verified', 'reset_otp_last_sent_at']);

            return redirect()->route('password.forgot')->withErrors(['user' => 'Kode OTP reset sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        if (!Hash::check($request->otp, $resetToken->token)) {
            return back()->withErrors(['otp' => 'Kode OTP reset salah.']);
        }

        Session::put('reset_otp_verified', true);

        return redirect()->route('password.reset.form');
    }

    public function resendPasswordResetOtp(Request $request)
    {
        $email = Session::get('reset_email');
        $lastSentAt = Session::get('reset_otp_last_sent_at');

        if (!$email) {
            return redirect()->route('password.forgot')->withErrors(['user' => 'Sesi reset password berakhir. Silakan mulai ulang.']);
        }

        if ($lastSentAt && now()->timestamp - $lastSentAt < 60) {
            $remainingSeconds = 60 - (now()->timestamp - $lastSentAt);

            return back()->withErrors(['otp' => "Tunggu {$remainingSeconds} detik sebelum kirim ulang OTP."]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            Session::forget(['reset_email', 'reset_otp_verified', 'reset_otp_last_sent_at']);

            return redirect()->route('password.forgot')->withErrors(['user' => 'Akun tidak ditemukan.']);
        }

        $otp = (string) random_int(100000, 999999);
        $phone = $user->hp ?? $user->no_hp;

        if (!$this->sendOtpWhatsapp($phone, $otp)) {
            return back()->withErrors(['otp' => 'Gagal mengirim ulang OTP reset password. Silakan coba lagi.']);
        }

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        Session::put([
            'reset_otp_verified' => false,
            'reset_otp_last_sent_at' => now()->timestamp,
        ]);

        return back()->with('success', 'Kode OTP reset password baru telah dikirim ke WhatsApp Anda.');
    }

    public function showResetPasswordForm()
    {
        if (!Session::get('reset_email') || !Session::get('reset_otp_verified')) {
            return redirect()->route('password.forgot');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = Session::get('reset_email');

        if (!$email || !Session::get('reset_otp_verified')) {
            return redirect()->route('password.forgot')->withErrors(['user' => 'Sesi reset password berakhir. Silakan mulai ulang.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.forgot')->withErrors(['user' => 'Akun tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        Session::forget(['reset_email', 'reset_otp_verified', 'reset_otp_last_sent_at']);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }

    private function sendOtpWhatsapp($phone, $otp)
    {
        try {
            $url = "https://api.fonnte.com/send";
            $token = "uyR8eeAst6ZuPiKR8uHj";

            $target = preg_replace('/[^0-9]/', '', (string) $phone);

            if (!$target) {
                Log::warning('Nomor HP kosong, gagal kirim OTP WhatsApp');
                return false;
            }

            if (substr($target, 0, 1) === '0') {
                $target = '62' . substr($target, 1);
            }

            $message = "*FUTSALHUB OTP*\n\n" .
                "Kode OTP Anda adalah: *{$otp}*\n\n" .
                "Jangan berikan kode ini kepada siapa pun.";

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: $token"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                Log::error('Gagal kirim OTP WhatsApp', ['error' => $err]);
                return false;
            }

            Log::info('OTP WhatsApp terkirim', ['response' => $response]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error OTP WhatsApp', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
