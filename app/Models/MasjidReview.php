<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasjidReview extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'masjid_id', 'rating', 'comment', 'img'];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
