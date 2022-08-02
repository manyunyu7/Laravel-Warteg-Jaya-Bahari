<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $appends = ["img_full_path","type_food_name","category_name"];


    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeFood::class);
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class);
    }

    public function getImgFullPathAttribute(){
        return asset("").$this->image;
    }

    public function getCategoryNameAttribute(){
        $obj = FoodCategory::all();
        $retVal = "";
        foreach ($obj as $key) {
            if($key->id==$this->type_food_id){
                $retVal = $key->name; 
            }
        }
        return $retVal;
    }

    public function getTypeFoodNameAttribute(){
        $typeFood = TypeFood::all();
        $retVal = "";
        foreach ($typeFood as $key) {
            if($key->id==$this->type_food_id){
                $retVal = $key->name; 
            }
        }
        return $retVal;
    }
    
}
