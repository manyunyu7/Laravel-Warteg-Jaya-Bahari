<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoranReviewImage extends Model
{
    use HasFactory;

    public function restoranReview()
    {
        return $this->belongsTo(RestoranReview::class);
    }
}
