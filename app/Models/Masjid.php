<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masjid extends Model
{
    use HasFactory;

    protected $fillable = ['name','lat','long','img','type_id', 'facilities', 'phone', 'operating_start', 'operating_end', 'address'];
    protected $casts = [
        'facilities' => 'array',
    ];

    public function reviews()
    {
        $this->hasMany(MasjidReview::class);
    }

    public function type()
    {
        $this->belongsTo(MasjidType::class);
    }

    public function userFavorite()
    {
        $this->belongsToMany(FavoriteMasjid::class);
    }
}
