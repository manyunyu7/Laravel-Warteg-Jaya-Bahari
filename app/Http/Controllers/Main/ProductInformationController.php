<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\ProductInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $informations = ProductInformation::all();

        if ($informations == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'informations data not found', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get informations', 
                'data' => $informations
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
            'product_id' => 'required',
            'allergy' => 'required|string|min:3|max:100',
            'environment' => 'required|string|min:3|max:100',
            'ingredient' => 'required|string|min:3|max:100',
            'summary' => 'required|string'
        ],
        [
            'product_id.required' => 'product_id cannot be empty',
            'allergy.required' => 'allergy cannot be empty',
            'environment.required' => 'environment cannot be empty',
            'ingredient.required' => 'ingredients cannot be empty',
            'summary.required' => 'summary cannot be empty'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $information = new ProductInformation();
        $information->product_id = $request->product_id;
        $information->allergy = $request->allergy;
        $information->environment = $request->environment;
        $information->ingredient = $request->ingredient;
        $information->summary = $request->summary;

        if ($information->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add information', 
                'data' => $information
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add information', 
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
    public function show($informationId)
    {
        $information = ProductInformation::find($informationId);

        if ($information == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'information detail not found', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get information detail', 
                'data' => $information
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
    public function update(Request $request, $informationId)
    {
        $validator = Validator::make($request->all(), 
        [
            'allergy' => 'string|min:3|max:100',
            'environment' => 'string|min:3|max:100',
            'ingredient' => 'string|min:3|max:100',
        ],
        [
            'allergy.string' => 'allergy must be a string',
            'environment.string' => 'environment must be a string',
            'ingredient.string' => 'ingredients must be a string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $information = ProductInformation::find($informationId);

        if ($information == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'informations not found', 
                'data' => null
            ]);
        }

        $information->allergy = $request->allergy;
        $information->environment = $request->environment;
        $information->ingredient = $request->ingredient;

        if ($information->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update information data', 
                'data' => $information
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update information data', 
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
    public function destroy($informationId)
    {
        $information = ProductInformation::find($informationId);

        if ($information == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'information not found', 
                'data' => null
            ]);
        }else{
            if ($information->delete()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success delete information data', 
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed delete information data', 
                ]);
            }
        }
    }
}
