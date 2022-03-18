<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masjid extends Model
{
    use HasFactory;

    protected $fillable = ['name','lat','long','img'];

    public function ratings()
    {
        $this->hasMany(MasjidReview::class);
    }
}
