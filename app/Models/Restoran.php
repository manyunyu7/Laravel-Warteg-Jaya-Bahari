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
}
