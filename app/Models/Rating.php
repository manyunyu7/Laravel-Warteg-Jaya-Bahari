<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function masjids()
    {
        $this->hasMany(MasjidReview::class);
    }

    public function restorans()
    {
        $this->hasMany(RestoranReview::class);
    }
}
