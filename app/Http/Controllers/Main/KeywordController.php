<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keywords = Keyword::all();

        if (!$keywords) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed get keywrd data', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get keyword data', 
                'data' => $keywords
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
            'name' => 'required|string|between:3,100',
        ],
        [
            'name.required' => 'name cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $keyword = new Keyword();
        $keyword->name = $request->name;
        $keyword->save();

        if (! $keyword) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed store keyword data', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success store keyword data', 
                'data' => $keyword
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($keywordId)
    {
        $keyword = Keyword::find($keywordId);

        if (! $keyword->exists()) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'keyword not found', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get data keyword', 
                'data' => $keyword
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
    public function update(Request $request, $keywordId)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'string|between:3,100',
        ],
        [
            'name.required' => 'name cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $keyword = Keyword::find($keywordId);

        if (!$keyword) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'keyword data not found', 
                'data' => null
            ]);
        }

        $keyword->name = $request->name;
        

        if (!$keyword->save()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed get keyword data', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'sucess get keyword data', 
                'data' => $keyword
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($keywordId)
    {
        $keyword = Keyword::find($keywordId);

        if (!$keyword) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'keyword data not found', 
                'data' => null
            ]);
        }

        if (!$keyword->delete()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed delete keyword data', 
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'suceess delete keyword data', 
            ]);
        }
    }
}
