<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\FotoLapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index(Request $request) 
    {
        $query = Lapangan::query();
        if ($request->filled('area')) {
            $query->where('lokasi', 'like', '%' . $request->area . '%');
        }
        if ($request->filled('tipe_rumput')) {
            $query->where('tipe_rumput', $request->tipe_rumput);
        }
        $lapangan = $query->get();
        return view('lapangan.index', compact('lapangan'));
    }

    public function adminIndex() 
    {
        $fields = Lapangan::with('galeri')->get();
        return view('admin.gallery.index', compact('fields'));
    }

    public function adminStore(Request $request) 
    {
        $request->validate([
            'nama_lapangan' => 'required',
            'harga'         => 'required|numeric',
            'foto'          => 'required|file|mimetypes:image/jpeg,image/png,image/webp|max:4096',
            'lokasi'        => 'required',
            'foto_galeri.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp|max:4096',
        ]);

        $data = [
            'nama_lapangan' => $request->nama_lapangan,
            'lokasi'        => $request->lokasi ?? 'Area Bandung Pusat',
            'alamat_lengkap'=> $request->alamat_lengkap ?? null,
            'link_maps'     => $request->link_maps ?? null,
            'latitude'      => $request->latitude ?? null,
            'longitude'     => $request->longitude ?? null,


            'tipe_rumput'   => $request->tipe_rumput,


            'harga_per_jam' => $request->harga,
            'fasilitas'     => $request->fasilitas ?? '-',
            'deskripsi'     => $request->deskripsi ?? '-',
            'is_active'     => $request->boolean('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('lapangan', 'public');
            $data['foto'] = $path;
        }

        $lapangan = Lapangan::create($data);

        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $galeri) {
                $path = $galeri->store('lapangan', 'public');
                FotoLapangan::create([
                    'lapangan_id' => $lapangan->lapangan_id,
                    'path_foto'   => $path,
                ]);
            }
        }

        return back()->with('success', 'Data Lapangan Berhasil Disimpan!');
    }

    public function adminEdit($id) 
    {
        $field = Lapangan::with('galeri')->findOrFail($id);
        return response()->json($field);
    }

    public function adminUpdate(Request $request, $id) 
    {
        $field = Lapangan::findOrFail($id);

        $request->validate([
            'nama_lapangan' => 'required',
            'harga'         => 'required|numeric',
            'lokasi'        => 'required',
            'foto'          => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp|max:4096',
            'foto_galeri.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp|max:4096',
        ]);

        $isActive = $request->boolean('is_active') ? 1 : 0;

        $data = [
            'nama_lapangan' => $request->nama_lapangan,
            'lokasi'        => $request->lokasi ?? 'Area Bandung Pusat',
            'alamat_lengkap'=> $request->alamat_lengkap ?? null,
            'link_maps'     => $request->link_maps ?? null,
            'latitude'      => $request->latitude ?? null,
            'longitude'     => $request->longitude ?? null,
            'tipe_rumput'   => $request->tipe_rumput,
            'harga_per_jam' => $request->harga,
            'fasilitas'     => $request->fasilitas ?? '-', 
            'deskripsi'     => $request->deskripsi ?? '-',
            'is_active'     => $isActive,
        ];

        if ($request->hasFile('foto')) {
            if ($field->foto && Storage::disk('public')->exists($field->foto)) {
                Storage::disk('public')->delete($field->foto);
            }
            $path = $request->file('foto')->store('lapangan', 'public');
            $data['foto'] = $path;
        }

        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $galeri) {
                $path = $galeri->store('lapangan', 'public');
                FotoLapangan::create([
                    'lapangan_id' => $field->lapangan_id,
                    'path_foto'   => $path,
                ]);
            }
        }

        $field->update($data);

        return back()->with('success', 'Data Lapangan Berhasil Diperbarui!');
    }

    public function adminDestroy($id)
    {
        $field = Lapangan::with('galeri')->findOrFail($id);

        if ($field->foto && Storage::disk('public')->exists($field->foto)) {
            Storage::disk('public')->delete($field->foto);
        }

        foreach ($field->galeri as $galeri) {
            if (Storage::disk('public')->exists($galeri->path_foto)) {
                Storage::disk('public')->delete($galeri->path_foto);
            }
            $galeri->delete();
        }

        $field->delete();

        return back()->with('success', 'Lapangan Berhasil Dihapus Permanen!');
    }

    public function adminGalleryDestroy($lapanganId, $fotoId)
    {
        $foto = FotoLapangan::where('id', $fotoId)
                             ->where('lapangan_id', $lapanganId)
                             ->first();
        
        if ($foto) {
            if (Storage::disk('public')->exists($foto->path_foto)) {
                Storage::disk('public')->delete($foto->path_foto);
            }
            $foto->delete();
        }

        return back()->with('success', 'Foto Galeri Berhasil Dihapus!');
    }

    public function show($id)
    {
        $lapangan = Lapangan::with('galeri')->where('lapangan_id', $id)->firstOrFail();

        // Load STATUS OPERASIONAL HARI INI (Pagi, Siang, Malam)
        $operasionalToday = \App\Models\LapanganOperasionalToday::where('lapangan_id', $id)->get()->keyBy('slot');

        return view('lapangan.show', compact('lapangan', 'operasionalToday'));
    }
}
