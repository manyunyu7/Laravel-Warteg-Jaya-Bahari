<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masjid extends Model
{
    use HasFactory;

    protected $fillable = ['name','lat','long','img'];
    protected $casts = [
        'operating_start' => 'hh:mm',
        'operating_end' => 'hh:mm',
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
}
