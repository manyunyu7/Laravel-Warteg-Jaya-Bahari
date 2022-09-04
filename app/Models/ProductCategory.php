<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $appends = ["image"];

    public function products()
    {
        $this->hasMany(Product::class);
    }

    public function getImageAttribute(){
        return "http://feylabs.my.id/fm/skripsweet/cdn/product_category/$this->name.png";
    }
}
