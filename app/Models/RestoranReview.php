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

    //start #mobreq
    protected $appends =  ['review_category', 'review_photos','user_info'];

    public function getUserInfoAttribute(){
        $user = User::find($this->user_id);
        return $user;
    }

    public function getReviewCategoryAttribute()
    {
        $ratings = Rating::all();
        $ratingCategory = "";
        foreach ($ratings as $item) {
            if ($item->id == $this->rating_id) {
                $ratingCategory = $item->name;
            }
        }
        return $ratingCategory;
    }


    public function getReviewPhotosAttribute()
    {
        $ratings = RestoranReviewImage::where('restoran_review_id', '=', $this->id)->get();
        $arrayPhotoUrl = array();
        foreach ($ratings as $obj) {
            array_push($arrayPhotoUrl, url("/") . "/" . $obj->path);
        }
        return $arrayPhotoUrl;
    }
}
