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
        $query = Lapangan::query()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->withCount([
                'bookings as successful_bookings_count' => fn ($bookingQuery) => $bookingQuery
                    ->where('status', 'Success'),
            ]);

        if ($request->filled('search')) {
            $query->where('nama_lapangan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('area')) {
            $query->where('lokasi', 'like', '%' . $request->area . '%');
        }

        if ($request->filled('tipe_rumput')) {
            $query->where('tipe_rumput', $request->tipe_rumput);
        }

        match ($request->query('sort', 'latest')) {
            'latest' => $query->orderByDesc('lapangan_id'),
            'price_asc' => $query
                ->orderBy('harga_per_jam')
                ->orderBy('nama_lapangan'),
            'rating_desc' => $query
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->orderBy('nama_lapangan'),
            'popular_desc' => $query
                ->orderByDesc('successful_bookings_count')
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->orderBy('nama_lapangan'),
            default => $query->orderByDesc('lapangan_id'),
        };

        $lapangan = $query->get();
        $favoriteIds = $request->user()
            ? $request->user()
                ->favoriteLapangans()
                ->pluck('lapangan.lapangan_id')
                ->all()
            : [];

        return view('lapangan.index', compact('lapangan', 'favoriteIds'));
    }

    public function favorites(Request $request)
    {
        $lapangan = $request->user()
            ->favoriteLapangans()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('favorite_lapangans.created_at', 'desc')
            ->get();

        $favoriteIds = $lapangan->pluck('lapangan_id')->all();

        return view('lapangan.favorites', compact('lapangan', 'favoriteIds'));
    }

    public function toggleFavorite(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $favorites = $request->user()->favoriteLapangans();
        $isFavorite = $favorites
            ->where('lapangan.lapangan_id', $lapangan->lapangan_id)
            ->exists();

        if ($isFavorite) {
            $favorites->detach($lapangan->lapangan_id);
            $message = $lapangan->nama_lapangan . ' dihapus dari GOR favorit.';
            $status = 'removed';
        } else {
            $favorites->attach($lapangan->lapangan_id);
            $message = $lapangan->nama_lapangan . ' ditambahkan ke GOR favorit.';
            $status = 'added';
        }

        return back()
            ->with('favorite_message', $message)
            ->with('favorite_status', $status);
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
            'foto_galeri'   => 'nullable|array|max:4',
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
            'is_active'     => 1,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('lapangan', 'public');
            $data['foto'] = $path;
        }

        $lapangan = Lapangan::create($data);

        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $slot => $galeri) {
                FotoLapangan::create([
                    'lapangan_id' => $lapangan->lapangan_id,
                    'position' => (int) $slot + 1,
                    'path_foto'   => $galeri->store('lapangan', 'public'),
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
        $field = Lapangan::with('galeri')->findOrFail($id);

        $request->validate([
            'nama_lapangan' => 'required',
            'harga'         => 'required|numeric',
            'lokasi'        => 'required',
            'foto'          => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp|max:4096',
            'foto_galeri'   => 'nullable|array|max:4',
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
        ];

        if ($request->hasFile('foto')) {
            if ($field->foto && Storage::disk('public')->exists($field->foto)) {
                Storage::disk('public')->delete($field->foto);
            }
            $path = $request->file('foto')->store('lapangan', 'public');
            $data['foto'] = $path;
        }

        if ($request->hasFile('foto_galeri')) {
            $fotoLamaPerSlot = $field->galeri->keyBy('position');

            foreach ($request->file('foto_galeri') as $slot => $galeri) {
                $position = (int) $slot + 1;
                $pathBaru = $galeri->store('lapangan', 'public');
                $fotoLama = $fotoLamaPerSlot->get($position);

                if ($fotoLama) {
                    if (Storage::disk('public')->exists($fotoLama->path_foto)) {
                        Storage::disk('public')->delete($fotoLama->path_foto);
                    }

                    $fotoLama->update(['path_foto' => $pathBaru]);
                    continue;
                }

                FotoLapangan::create([
                    'lapangan_id' => $field->lapangan_id,
                    'position' => $position,
                    'path_foto' => $pathBaru,
                ]);
            }
        }

        $field->update($data);

        return back()->with('success', 'Data Lapangan Berhasil Diperbarui!');
    }

    public function adminToggleStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $field = Lapangan::findOrFail($id);
        $field->is_active = $validated['is_active'];
        $field->save();

        $message = $field->is_active
            ? 'Lapangan berhasil diaktifkan kembali.'
            : 'Lapangan berhasil dinonaktifkan.';

        return back()->with('success', $message);
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
        $lapangan = Lapangan::with([
                'galeri',
                'reviews' => fn ($query) => $query->with('user')->latest(),
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('lapangan_id', $id)
            ->firstOrFail();

        // Load STATUS OPERASIONAL HARI INI (Pagi, Siang, Malam)
        $operasionalToday = \App\Models\LapanganOperasionalToday::where('lapangan_id', $id)->get()->keyBy('slot');

        return view('lapangan.show', compact('lapangan', 'operasionalToday'));
    }
}
