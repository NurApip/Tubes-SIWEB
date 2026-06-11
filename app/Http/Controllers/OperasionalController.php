<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\LapanganOperasionalToday;
use Illuminate\Http\Request;

class OperasionalController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::all();
        return view('admin.operasional.index', compact('lapangans'));
    }

    public function update(Request $request, $lapanganId)
    {
        $request->validate([
            'slot' => 'required|string|in:Pagi,Siang,Sore,Malam',
            'state' => 'required|in:0,1',
        ]);

        $lapangan = Lapangan::findOrFail($lapanganId);

        $row = LapanganOperasionalToday::firstOrCreate([
            'lapangan_id' => $lapangan->lapangan_id,
            'slot' => $request->slot,
        ]);

        $row->is_full = $request->input('state') === '1';
        $row->save();

        return back()->with('success', 'Operasional berhasil diperbarui!');
    }
}

