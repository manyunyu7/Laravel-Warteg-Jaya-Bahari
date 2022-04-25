<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id','certification_id', 'name','code', 'img',];

    public function certification()
    {
        return $this->hasOne(Certification::class);
    }

    public function information()
    {
        return $this->belongsto(ProductInformation::class);
    }

    public function category()
    {
        return $this->belongsto(ProductCategory::class);
    }
}
