<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FoodController extends Controller
{
    public function store(Request $request)
    {
        $restoran_id = (int)$request->restoran_id;
        $type_food_id = (int)$request->type_food_id;
        $category_id = (int)$request->category_id;
        $validator = Validator::make($request->all(),
        [
            'restoran_id' => 'required|',
            'type_food_id'  => 'required', Rule::in([1, 2, 3, 4,5,6,7,8,9]),
            'category_id'  => 'required', Rule::in([1, 2, 3, 4,5,6]),
            'name' => 'required|string|min:4',
            'description' => 'required|string|min:3|max:1000',
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|integer',
        ],
        [
            'restoran_id.required' => 'restoran_id cannot be empty',
            'type_food_id.required' => 'food_id cannot be empty',
            'category_id.required' => 'category_id cannot be empty',
            'name.required' => 'name cannot be empty',
            'description.required' => 'description cannot be empty',
            'image.required' => 'image cannot be empty',
            'img.image' => 'Image must be and image',
            'price.required' => 'price cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $food = new Food();
        $food->restoran_id = $restoran_id;
        $food->type_food_id = $type_food_id;
        $food->category_id = $category_id;
        $food->name = $request->name;
        $food->description = $request->description;
        $food->price = $request->price;
        if ($request->has('quantity')) {
            $quantity = (int) $request->quantity;
            if(is_int($quantity)){
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'quantity must be a number',
                ]);
            }
            $food->quantity = $quantity;
            $food->is_visible = $food->quantity === 0? false: true;
        }
        $food->is_visible = false;
        

        $img = $request->file('image');
        $path = 'uploads/img/foods';
        $ekstension = $img->getClientOriginalExtension();
        $name = 'Food'.'_'.uniqid().'.'.$ekstension;

        if ($request->image->move(public_path($path),$name)) {
            $food->image = $path.$name;
            

            if ($food->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Success store food',
                    'data' => $food,
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed store food',
                    'data' => null,
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed upload image',
                'data' => null,
            ]);
        }
    }

    public function getFood($restoId, $categoryId)
    {
        $foods = Food::where('restoran_id', $restoId)->where('category_id', $categoryId)->get();
        if (!$foods) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Data not Found',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get data foods',
            'data' => $foods,
        ]);
    }

    public function delete($foodId)
    {
        $food = Food::find($foodId);

        if (!$food) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Data food not Found',
                'data' => null,
            ]);
        }

        if ($food->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success delete data foods',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed delete data foods',
            ]);
        }
    }
}
