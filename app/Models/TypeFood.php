<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeFood extends Model
{
    use HasFactory;

    public function restorans()
    {
        return $this->hasMany(Restoran::class);
    }
    
    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
