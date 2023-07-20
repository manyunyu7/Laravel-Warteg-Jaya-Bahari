<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Restoran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
//                'type_food_id' => 'required', Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9]),
//                'category_id' => 'required', Rule::in([1, 2, 3, 4, 5, 6]),
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

        //$restricted = Restoran::where('id', $restoran_id)->first()->user_id;

        // if ($restricted != Auth::id()) {
        //     return response()->json([
        //         'success' => false,
        //         'code' => 403,
        //         'message' => 'Restriced user cannot create food',
        //     ]);
        // }

        $food = new Food();
        $food->restoran_id = $restoran_id;
        $food->type_food_id = $type_food_id;
        $food->category_id = $category_id;
        $food->name = $request->name;
        $food->description = $request->description;
        $food->price = $request->price;
        if ($request->has('quantity')) {
            $quantity = (int)$request->quantity;
            if (!is_int($quantity)) {
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'quantity must be a number',
                ], 422);
            }
            $food->quantity = $quantity;
            $food->is_visible = $food->quantity === "0" ? false : true;
        } else {
            $food->is_visible = false;
        }

        $img = $request->file('image');
        $ekstension = $img->getClientOriginalExtension();
        if ($ekstension == null) {
            $ekstension = "jpg";
        }

        $name = 'Food' . '_' . time() . "_" . uniqid() . '.' . $ekstension;
        $path_string = "storage/food";
        $path = public_path($path_string);
        if ($request->image->move($path, $name)) {
            $food->image = $path_string . "/" . $name;


            if ($food->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Success store food',
                    'data' => $food,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed store food',
                    'data' => null,
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed upload image',
                'data' => null,
            ], 400);
        }
    }

    public function getFood($restoId, $categoryId)
    {
        $foods = Food::where([
            'restoran_id' => $restoId,
            'category_id' => $categoryId
        ])->get();

        if ($foods == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Data not Found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get data foods',
            'data' => $foods,
        ], 200);
    }

    public function editFood(Request $request, $foodId)
    {
        $restoran_id = (int)$request->restoran_id;
        $type_food_id = (int)$request->type_food_id;
        $category_id = (int)$request->category_id;
        $validator = Validator::make($request->all(),
            [
//                'type_food_id' => Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9]),
//                'category_id' => Rule::in([1, 2, 3, 4, 5, 6]),
                'name' => 'string|min:4',
                'description' => 'string|min:3|max:1000',
                'image' => 'image:jpeg,png,jpg,gif,svg|max:2048',
                'price' => 'integer',
            ],
            [
                'name.string' => 'name must be a string',
                'description.string' => 'description a string',
                'img.image' => 'Image must be and image',
                'price.integer' => 'price must be an integer',
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $name = "";
        $food = Food::where('id', $foodId)->first();

        if (!$food) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Data not Found',
                'data' => null,
            ], 404);
        }

        $food->restoran_id = $restoran_id;
        $food->type_food_id = $type_food_id;
        $food->category_id = $category_id;
        $food->name = $request->name;
        $food->description = $request->description;
        $food->price = $request->price;
        if ($request->has('quantity')) {
            $quantity = (int)$request->quantity;
            if (!is_int($quantity)) {
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'quantity must be a number',
                ], 422);
            }
            $food->quantity = $quantity;
            $food->is_visible = $food->quantity === "0" ? false : true;
        } else {
            $food->is_visible = false;
        }

        if ($request->hasFile('image')) {
            $path = public_path('storage/') . $food->image;


            if (file_exists($path)) {
                try {
                    unlink($path);
                } catch (Exception $e) {
//                    return response()->json([
//                        'success' => false,
//                        'code' => 400,
//                        'message' => $e
//                    ], 400);
                }
            }

            $img = $request->file('image');
            $ekstension = $img->getClientOriginalExtension();
            if ($ekstension == null) {
                $ekstension = "jpg";
            }

            $name = 'Food' . '_' . time() . "_" . uniqid() . '.' . $ekstension;
            $path_string = "storage/food";
            $path = public_path($path_string);
            if ($request->image->move($path, $name)) {
                $food->image = $path_string . "/" . $name;
            }
        }


        if ($food->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success edit food',
                'data' => $food,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed edit food',
                'data' => null,
            ], 400);
        }
    }

    public function delete($foodId)
    {
        $food = Food::find($foodId);

        if ($food == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Data food not Found',
                'data' => null,
            ], 404);
        }

        if ($food->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success delete data foods',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed delete data foods',
            ], 400);
        }
    }

    function getFoodDetail($id){
        return Food::findOrFail($id);
    }
}
