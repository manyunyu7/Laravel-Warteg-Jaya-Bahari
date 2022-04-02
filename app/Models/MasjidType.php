<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasjidType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function masjids()
    {
        $this->hasMany(Masjid::class);
    }
}
