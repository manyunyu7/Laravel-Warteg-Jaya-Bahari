<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodCategoryController extends Controller
{
    public function store(Request $request, $restoId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|min:3|max:255',
            ],
            [
                'name.required' => 'name cannot be empty',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $cekResto = Restoran::where('id', $restoId)->first();

        if (!$cekResto) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found', 
                'data' => null
            ],404);
        }

        $category = new FoodCategory();
        
        $category->resto_id = $restoId;
        $category->name = $request->name;

        if ($category->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add category food',
                'data' => $category
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add category food',
                'data' => null
            ],400);
        }
    }

    public function index()
    {
        $categorys = FoodCategory::all();

        if (!$categorys) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed get food category data', 
                'data' => null
            ],400);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get food category data', 
                'data' => $categorys
            ],200);
        }
    }

    public function getByRestoran($restoId)
    {
        $checkResto = Restoran::where('id', $restoId)->first();
        if (!$checkResto) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found', 
                'data' => null
            ],404);
        }

        $category = FoodCategory::where('resto_id', $restoId)->get();
        if (!$category) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Category not found', 
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get category data', 
            'data' => $category
        ],200);
        
    }

    public function getDetail($categoryId)
    {
        $category = FoodCategory::find($categoryId);

        if (!$category) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Category not found', 
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get category data', 
            'data' => $category
        ],200);
    }

    public function update(Request $request, $categoryId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'resto_id' => 'integer',
                'name' => 'string|min:3|max:255',
            ],
            [
                'resto_id.integer' => 'resto_id must be a number',
                'name.required' => 'name cannot be empty',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $category = FoodCategory::find($categoryId);

        if (!$category) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Category not found', 
                'data' => null
            ],404);
        }

        $category->resto_id = $request->resto_id;
        $category->name = $request->name;

        if ($category->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update food category data', 
                'data' => $category
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update food category data', 
                'data' => null
            ],400);
        }

    }

    public function destroy($categoryId)
    {
        $foods = Food::where('category_id',$categoryId)->get();

        if ($foods != null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'cannot delete category',
            ],404);
        }
        
        $category = FoodCategory::find($categoryId);

        if ($category == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'food category data not found',
            ],404);
        }

        if ($category->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete food category'
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete food category', 
            ],400);
        }
    }
}
