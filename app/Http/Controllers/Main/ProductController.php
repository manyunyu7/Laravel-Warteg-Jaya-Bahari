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
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get products data', 
                'data' => $products
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $name = time().'_'.$product->name.'.'.$ekstension;
        $request->img->move(public_path('uploads/img/products'), $name);

        $product->img = $name;

        if ($product->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add product', 
                'data' => $product
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add product', 
                'data' => null
            ]);
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
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get data product', 
                'data' => $product
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
            ]);
        }

        $product->certification_id = $request->certification_id;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->code = $request->code;

        if ($request->hasFile('img')) {
            $path = public_path('uploads/img/products/').$product->img;

            if (file_exists($path)) {
                try {
                    unlink($path);
                } catch (Throwable $e) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            $file = $request->file('img');
            $ekstension = $file->getClientOriginalExtension();
            $name = time().'_'.$product->name.'.'.$ekstension;
            $request->img->move(public_path('uploads/img/products'), $name);

            $product->img = $name;
        }

        if ($product->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update product data', 
                'data' => $product
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update prodct data', 
                'data' => null
            ]);
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
            ]);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete product'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete product', 
            ]);
        }
    }
}
