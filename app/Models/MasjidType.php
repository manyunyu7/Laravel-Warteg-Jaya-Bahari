<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasjidType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected static function booted()
    {
        static::deleted(function ($masjid) {
            unlink(public_path('storage/'.$masjid->img));
        });

        static::updated(function ($masjid) {
            unlink(public_path('storage/'.$masjid->img));
        });
    }

    public function masjids()
    {
        $this->hasMany(Masjid::class);
    }
}
