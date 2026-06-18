<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Pilih rating terlebih dahulu.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
        ]);

        $created = DB::transaction(function () use ($request, $bookingId, $validated) {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', $request->user()->id)
                ->where('status', 'Success')
                ->lockForUpdate()
                ->firstOrFail();

            if (!$booking->lapangan_id || $booking->review()->exists()) {
                return false;
            }

            $booking->review()->create([
                'user_id' => $request->user()->id,
                'lapangan_id' => $booking->lapangan_id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            return true;
        });

        if (!$created) {
            return back()->with('error', 'Booking ini sudah direview atau data lapangannya tidak tersedia.');
        }

        return back()->with('success', 'Terima kasih, rating dan review berhasil disimpan.');
    }
}
