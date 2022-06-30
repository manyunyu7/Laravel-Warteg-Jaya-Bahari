<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasjidReviewImage extends Model
{
    use HasFactory;

    protected $fillable = ['masjid_review_id', 'path',];

    protected static function booted()
    {
        static::deleted(function ($masjidReview) {
            unlink(public_path('storage/'.$masjidReview->path));
        });
    }

    public function masjidReview()
    {
        return $this->belongsTo(MasjidReview::class);
    }
}
