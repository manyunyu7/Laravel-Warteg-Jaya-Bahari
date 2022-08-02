<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function restorans()
    {
        return $this->hasMany(Restoran::class);
    }
}
