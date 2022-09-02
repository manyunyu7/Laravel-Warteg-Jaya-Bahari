<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restoran extends Model
{
    use HasFactory;
    protected $appends = ["server_time","img_full_path","certification_name","food_type_name","is_resto_schedule_open","list_operating_hours"];
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

    public function getImgFullPathAttribute(){
        return url("")."$this->image";
    }

    public function getCertificationNameAttribute(){
        $object = Certification::where("id", '=', $this->certification_id)->first();
        return $object->name;
    }
    public function getFoodTypeNameAttribute(){
        $object = TypeFood::where("id", '=', $this->type_food_id)->first();
        return $object->name;
    }

    public function getListOperatingHoursAttribute(){
        return RestoranOperatingHour::where("restorans_id",$this->id)->get();
    }
    public function getServerTimeAttribute(){
        return Carbon::now()->format('H:i:s');
    }

    public function getIsRestoScheduleOpenAttribute(){
        $now = Carbon::now();
        $start = "";
        $end = "";

        $ops =  $this->getListOperatingHoursAttribute();
        $currentDay = Carbon::now()->dayOfWeek;

        foreach ($ops as $item){
            if ($item->day_code == $currentDay){
                $start = Carbon::createFromTimeString($item->hour_start);
                $end = Carbon::createFromTimeString($item->hour_end);
            }
        }

        if ($now->between($start, $end)) {
            return true;
        }else{
            return false;
        }
    }
}
