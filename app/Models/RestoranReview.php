<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoranReview extends Model
{
    protected $fillable = ['user_id', 'restoran_id', 'rating_id', 'comment'];
    use HasFactory;

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function images()
    {
        return $this->hasMany(MasjidReviewImage::class);
    }
}
