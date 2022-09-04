<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class FeProductController extends Controller
{

    public function getProductCategory()
    {
        $productCategories = ProductCategory::all();
        return $productCategories;
    }

    public function getByCategory(Request $request,$id){
        $perPage = $request->perPage;
        $page = $request->page;
        $datas = Product::where("category_id","=",$id)->paginate($perPage, ['*'], 'page', $page);
        return $datas;
    }

}
