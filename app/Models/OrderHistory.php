<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }
}
