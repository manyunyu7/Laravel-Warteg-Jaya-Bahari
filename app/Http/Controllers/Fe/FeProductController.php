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

    public function search(Request $request)
    {
        $name = $request->name;
        $category = $request->category;
        $code = $request->code;

        $obj =  Product::where([
            ['name', 'LIKE', '%'.$name.'%'],
            ['category_id', 'LIKE', $category],
            ['code', 'LIKE', $category],
        ])->get();
        return $obj;
    }

    public function getByCategory(Request $request,$id){
        $perPage = $request->perPage;
        $page = $request->page;
        $datas = Product::where("category_id","=",$id)->paginate($perPage, ['*'], 'page', $page);
        return $datas;
    }

}
