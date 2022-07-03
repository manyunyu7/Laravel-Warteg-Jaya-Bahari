<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id','certification_id','category_id', 'name','code', 'img',];

    protected static function booted()
    {
        static::deleted(function ($product) {
            unlink(public_path('storage/'.$product->img));
        });
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
