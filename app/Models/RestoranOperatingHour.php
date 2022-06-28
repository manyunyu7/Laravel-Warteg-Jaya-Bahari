<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoranOperatingHour extends Model
{
    use HasFactory;

    public function restorans()
    {
        return $this->belongsTo(Restoran::class);
    }
}
