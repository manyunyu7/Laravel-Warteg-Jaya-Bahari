<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restoran extends Model
{
    use HasFactory;

    public function typeFood()
    {
        return $this->belongsTo(TypeFood::class);
    }

    public function userFavorite()
    {
        return $this->belongsToMany(FavoriteRestoran::class);
    }

    public function operatingHours()
    {
        return $this->hasMany(RestoranOperatingHour::class);
    }

    public function reviews()
    {
        return $this->hasMany(RestoranReview::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function foodCategory()
    {
        return $this->hasMany(FoodCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
