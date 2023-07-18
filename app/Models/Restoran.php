<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Restoran extends Model
{
    use HasFactory;

    protected $appends = [
        "server_time", "img_full_path", "data_bangunan",
        "certification_name", "food_type_name", "review_avg",
        "is_resto_schedule_open", "list_operating_hours",
        "is_favorited", "is_claimed"
    ];

    public function typeFood()
    {
        return $this->belongsTo(TypeFood::class);
    }

    public function userFavorite()
    {
        return $this->belongsToMany(FavoriteRestoran::class);
    }

    public function operatingHours()
    {
        return $this->hasMany(RestoranOperatingHour::class);
    }

    public function reviews()
    {
        return $this->hasMany(RestoranReview::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function foodCategory()
    {
        return $this->hasMany(FoodCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function carts()
    {
        return $this->hasMany(OrderCart::class);
    }

    public function getImgFullPathAttribute()
    {
        if (str_contains($this->image, "/uploads"))
            return url("") . "$this->image";
        if (str_contains($this->image, "storage")) {
            return asset("") . "/" . $this->image;
        } else {
            return asset("") . "/storage/restoran/" . $this->image;
        }
    }

    public function getIsFavoritedAttribute()
    {
        if (Auth::check()) {
            $obj = FavoriteRestoran::where([
                ['user_id', '=', Auth::id()],
                ['restorans_id', '=', $this->id],
            ])->count();

            if ($obj != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCertificationNameAttribute()
    {
        $object = Certification::where("id", '=', $this->certification_id)->first();
        return $object->name;
    }

    public function getFoodTypeNameAttribute()
    {
        $object = TypeFood::where("id", '=', $this->type_food_id)->first();
        return $object->name;
    }

    public function getListOperatingHoursAttribute()
    {
        return RestoranOperatingHour::where("restorans_id", $this->id)->get();
    }

    public function getServerTimeAttribute()
    {
        return Carbon::now()->format('H:i:s');
    }

    public function getReviewAvgAttribute()
    {
        $masjidReviews = RestoranReview::where("restoran_id", '=', $this->id)->get();
        $object = new \stdClass();

        $ratings1 = 0;
        $ratings2 = 0;
        $ratings3 = 0;
        $ratings4 = 0;
        $ratings5 = 0;

        foreach ($masjidReviews as $data) {
            if ($data->rating_id == 1) {
                $ratings1 += 1;
            }
            if ($data->rating_id == 2) {
                $ratings2 += 1;
            }
            if ($data->rating_id == 3) {
                $ratings3 += 1;
            }
            if ($data->rating_id == 4) {
                $ratings4 += 1;
            }
            if ($data->rating_id == 5) {
                $ratings5 += 1;
            }
        }
        $totalRatings = ((1.0 * $ratings1) + (2.0 * $ratings2) + (3.0 * $ratings3) + (4.0 * $ratings4) + (5.0 * $ratings5));

        $ratingCounts = $masjidReviews->count();
        $avg = 0;

        if ($totalRatings != 0) {
            $avg = $totalRatings / $ratingCounts;
        }

        $object->avg = round($avg);
        $object->rating1 = $ratings1;
        $object->rating2 = $ratings2;
        $object->rating3 = $ratings3;
        $object->rating4 = $ratings4;
        $object->rating5 = $ratings5;
        return round($avg);
    }

    public function getIsClaimedAttribute()
    {
        return true;
        $user = User::find($this->user_id);
        if ($user != null) {
            if ($user->roles_id != 1) {
                return true;
            } else return false;
        }
        return false;
    }


    public function getIsRestoScheduleOpenAttribute()
    {
        return true;
        $now = Carbon::now();
        $start = "";
        $end = "";

        $ops = $this->getListOperatingHoursAttribute();
        $currentDay = Carbon::now()->dayOfWeek;

        foreach ($ops as $item) {
            if ($item->day_code == $currentDay) {
                $start = Carbon::createFromTimeString($item->hour_start);
                $end = Carbon::createFromTimeString($item->hour_end);
            }
        }

        if ($now->between($start, $end)) {
            return true;
        } else {
            return false;
        }
    }

    public function getDataBangunanAttribute()
    {
        return DataBangunanResto::where('resto_id', '=', $this->id)->first();
    }
}
