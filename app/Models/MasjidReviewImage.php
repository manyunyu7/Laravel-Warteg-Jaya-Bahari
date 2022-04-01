<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasjidReviewImage extends Model
{
    use HasFactory;

    protected $fillable = ['masjid_review_id', 'path',];

    public function masjidReview()
    {
        return $this->belongsTo(MasjidReview::class);
    }
}
