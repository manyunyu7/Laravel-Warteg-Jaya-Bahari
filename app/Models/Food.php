<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeFood::class);
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class);
    }
}
