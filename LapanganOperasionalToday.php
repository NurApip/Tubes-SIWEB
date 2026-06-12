<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapanganOperasionalToday extends Model
{
    protected $table = 'lapangan_operasional_today';

    protected $fillable = [
        'lapangan_id',
        'slot',
        'is_full',
    ];

    protected $casts = [
        'is_full' => 'boolean',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }
}

