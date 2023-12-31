<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        if (!$products) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed get products data', 
                'data' => null
            ],400);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get products data', 
                'data' => $products
            ],200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            "certification_id" => "string", Rule::in([1,2,3,4]),
            "category_id" => "required|string", Rule::in([1,2,3,4,5,6,7,8,9,10,11,12]),
            "name" => "required|string|between:3,100",
            "code" => "required|string|min:3|max:100",
            'img' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            "certification_id.integer" => "certification_id must be an number",
            "category_id.integer" => "category_id cannot be empty",
            "name.required" => "name cannot be empty",
            "code.required" => "code cannot be empty",
            "img.image" => "Image must be an image",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = new Product();
        $product->certification_id = $request->certification_id;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->code = $request->code;

        $file = $request->file('img');
        $ekstension = $file->getClientOriginalExtension();
        $name = 'Product'.'_'.$product->name.'_'.uniqid().'.'.$ekstension;
        $request->img->move(public_path('storage'), $name);

        $product->img = $name;

        if ($product->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add product', 
                'data' => $product
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add product', 
                'data' => null
            ],400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        $product = Product::find($productId);

        if ($product == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'product not found', 
                'data' => null
            ],404);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get data product', 
                'data' => $product
            ],200);
        }
    }
    
    public function getByCategory($categoryId)
    {
        $product = Product::where('category_id', $categoryId)->get();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'product not found', 
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get data product by category', 
            'data' => $product
        ],200);
    }

    public function update(Request $request, $productId)
    {
        $validator = Validator::make($request->all(),
        [
            "certification_id" => "integer",
            "category_id" => "integer",
            "name" => "string|between:3,100",
            "code" => "string|min:3|max:1000",
            'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            "certification_id.integer" => "certification_id must be an number",
            "category_id" => "category_id must be an number",
            "name.string" => "name must be an string",
            "code.required" => "code must be an string",
            "img.image" => "Image must be an image",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = Product::find($productId);

        if ($product == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'product not found', 
                'data' => null
            ],404);
        }

        $product->certification_id = $request->certification_id;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->code = $request->code;

        if ($request->hasFile('img')) {
            $path = public_path('storgae').$product->img;

            if (file_exists($path)) {
                try {
                    unlink($path);
                } catch (Throwable $e) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e->getMessage(),
                    ],400);
                }
            }

            $file = $request->file('img');
            $ekstension = $file->getClientOriginalExtension();
            $name = 'Product'.'_'.$product->name.'_'.uniqid().'.'.$ekstension;
            $request->img->move(public_path('storage'), $name);

            $product->img = $name;
        }

        if ($product->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update product data', 
                'data' => $product
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update prodct data', 
                'data' => null
            ],400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId)
    {
        $product = Product::find($productId);

        if ($product == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'product not found', 
                'data' => null
            ],404);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete product'
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete product', 
            ],400);
        }
    }
}
