<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $totalMember = User::where('role', '!=', 1)->count();
        $totalSesi = Booking::count();
        $totalUang = Booking::sum('total_harga') ?? 0;
        $users = User::all();

        return view('admin.dashboard', compact('totalMember', 'totalSesi', 'totalUang', 'users'));
    }

    public function members()
    {
        $totalMember = User::where('role', '!=', 1)->count();
        $totalSesi = Booking::count();
        $totalUang = Booking::sum('total_harga') ?? 0;
        $users = User::where('role', '!=', 1)->get();

        return view('admin.members', compact('totalMember', 'totalSesi', 'totalUang', 'users'));
    }

    public function pendapatan()
    {
        $bookings = Booking::all();
        $totalPendapatan = Booking::sum('total_harga') ?? 0;

        return view('admin.pendapatan', compact('bookings', 'totalPendapatan'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'lapangan'])->orderBy('created_at', 'desc')->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function bookingDetail($id)
    {
        $booking = Booking::with(['user', 'lapangan'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function bookingUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Success,Cancelled',
        ]);

        $booking = Booking::findOrFail($id);

        // If setting to Success and kode_tiket not present, generate one
        if ($request->status === 'Success' && empty($booking->kode_tiket)) {
            // generate unique kode_tiket
            $kode = null;
            do {
                $kode = 'FH-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
            } while (Booking::where('kode_tiket', $kode)->exists());

            $booking->kode_tiket = $kode;
        }

        $booking->status = $request->status;
        $booking->save();

        // If status set to Success, attempt to notify customer via WhatsApp Gateway
        if ($booking->status === 'Success') {
            try {
                // Delegate to BookingController send method
                \App\Http\Controllers\BookingController::sendWaNotification($booking);
            } catch (\Exception $e) {
                Log::error('Failed to send WA notification', ['error' => $e->getMessage(), 'booking_id' => $booking->id]);
            }
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function bookingDestroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings')->with('success', 'Booking berhasil dihapus.');
    }

    public function memberUpdate(Request $request, $id)
    {
        $request->validate([
            'is_member' => 'required|in:0,1,2',
        ]);

        $user = User::where('role', '!=', 1)->findOrFail($id);

        $nextStatus = (int) $request->is_member;

        $user->is_member = $nextStatus;

        if ($nextStatus === 0) {
            if ($user->membership_bukti) {
                Storage::disk('public')->delete($user->membership_bukti);
            }

            $user->membership_bukti = null;
            $user->membership_requested_at = null;
        }

        $user->save();

        return back()->with('success', 'Status membership berhasil diperbarui.');
    }
}
