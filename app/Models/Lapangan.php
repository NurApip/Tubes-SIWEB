<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';
    protected $primaryKey = 'lapangan_id';

    protected $fillable = [
        'nama_lapangan',
        'tipe_rumput',
        'harga_per_jam',
        'foto',
        'fasilitas',
        'deskripsi',
        'lokasi',
        'alamat_lengkap',
        'link_maps',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function operasionalToday()
    {
        return $this->hasMany(\App\Models\LapanganOperasionalToday::class, 'lapangan_id', 'lapangan_id');
    }

    public function galeri()
    {
        return $this->hasMany(\App\Models\FotoLapangan::class, 'lapangan_id', 'lapangan_id');
    }
}
