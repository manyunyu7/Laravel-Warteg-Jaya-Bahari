<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forums = Forum::all();

        if ($forums->count() > 0) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get all forum data',
                'data' => $forums
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get all forum data',
                'data' => null
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
            'category_id' => 'required|integer', Rule::in([1, 2, 3, 4,5,6]),
            'title' => 'required|string|min:4',
            'body' => 'required|string|min:3|max:1000',
        ],
        [
            'category_id.required' => 'category_id cannot be empty',
            'title.required' => 'title cannot be empty',
            'body.required' => 'body cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $forum = new Forum();
        $forum->user_id = Auth::id();
        $forum->category_id = $request->category_id;
        $forum->title = $request->title;
        $forum->body = $request->body;

        if ($forum->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success store forum',
                'data' => $forum,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed store forum',
                'data' => null,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($forumId)
    {
        $forum = Forum::find($forumId);

        if ($forum == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'forum not found',
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success get detail forum',
                'data' => $forum
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
    public function update(Request $request, $forumId)
    {
        $validator = Validator::make($request->all(),
        [
            'category_id' => 'integer', Rule::in([1, 2, 3, 4,5,6]),
            'title' => 'string|min:4',
            'body' => 'string|min:3|max:1000',
        ],
        [
            'category_id.integer' => 'category_id must be a number',
            'title.string' => 'title must be a string',
            'body.string' => 'body must be a string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $forum = Forum::find($forumId);
        $forum->user_id = Auth::id();
        $forum->category_id = $request->category_id;
        $forum->title = $request->title;
        $forum->body = $request->body;

        if ($forum->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success upadate forum',
                'data' => $forum,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed update forum',
                'data' => null,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($forumId)
    {
        $forum = Forum::find($forumId);
        
        if ($forum == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'forum not found',
            ]);
        }else{
            if ($forum->delete()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success delete forum',
                ]);
            }
        }
    }
}
