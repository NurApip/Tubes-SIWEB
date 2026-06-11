<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lapangan;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings'; // Pastikan nama tabel sesuai di database

    protected $fillable = [
        'user_id',
        'lapangan_id',
        'nama_gor',
        'nama_penyewa',
        'nomor_wa',
        'no_hp',
        'tgl_main',
        'jam_mulai',
        'durasi',
        'durasi_bermain',
        'total_harga',
        'status',
        'bukti_bayar',
        'kode_tiket'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id', 'lapangan_id');
    }
}