<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id','certification_id','category_id', 'name','code', 'img',];
    protected $appends = ["img_full_path","product_information","certification_name","category_name"];

    protected static function booted()
    {
        static::deleted(function ($product) {
            unlink(public_path('storage/'.$product->img));
        });
    }

    public function getImgFullPathAttribute(){
        if (str_contains($this->img, "/uploads"))
            return url("") . "$this->image";
        if (str_contains($this->img, "storage")){
            return asset("") . "/" . $this->img;
        }else{
            return asset("") . "storage/" . $this->img;
        }
    }

    public function getProductInformationAttribute(){
        return ProductInformation::where("product_id","=",$this->id)->first();
    }

    public function getCertificationNameAttribute(){
        return Certification::findOrFail($this->certification_id)->name;
    }

    public function getCategoryNameAttribute(){
      return ProductCategory::findOrFail($this->category_id)->name;
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }

    public function information()
    {
        return $this->hasOne(ProductInformation::class);
    }

    public function category()
    {
        return $this->belongsto(ProductCategory::class);
    }
}
