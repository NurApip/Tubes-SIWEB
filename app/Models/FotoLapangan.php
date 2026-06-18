<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoLapangan extends Model
{
    use HasFactory;

    protected $table = 'foto_lapangans';
    protected $primaryKey = 'id';
    protected $fillable = ['lapangan_id', 'position', 'path_foto'];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id', 'lapangan_id');
    }
}
