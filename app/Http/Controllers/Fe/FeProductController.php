<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;

class FeProductController extends Controller
{

    public function getProductCategory()
    {
        $productCategories = ProductCategory::all();
        return $productCategories;
    }

    public function getByCategory($id){
        $datas = Product::where("category_id","=",$id)->get();
        return $datas;
    }

}
