<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForumCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = ForumComment::all();

        if ($comments->count() > 0) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get all comment data',
                'data' => $comments
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get all comment data',
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
            'forum_id' => 'required|integer',
            'comment' => 'required|string|min:3|max:1000',
        ],
        [
            'forum_id.required' => 'forum_id cannot be empty',
            'comment.required' => 'comment cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment = new ForumComment();
        $comment->user_id = Auth::id();
        $comment->forum_id = $request->forum_id;
        $comment->comment = $request->comment;
        
        if ($comment->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success to add comment',
                'data' => $comment,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed to add comment',
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
    public function show($commentId)
    {
        $comment = ForumComment::find($commentId);

        if ($comment == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Comment not found',
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success get detail comment',
                'data' => $comment
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
    public function update(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(),
        [
            'forum_id' => 'integer',
            'comment' => 'string|min:3|max:1000',
        ],
        [
            'forum_id.integer' => 'forum_id must be an integer',
            'comment.string' => 'comment must be an string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment = ForumComment::find($commentId);
        $comment->user_id = Auth::id();
        $comment->forum_id = $request->forum_id;
        $comment->comment = $request->comment;
        
        if ($comment->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success update comment',
                'data' => $comment,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed update comment',
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
    public function destroy($commentId)
    {
        $comment = ForumComment::find($commentId);

        if ($comment == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'comment not found',
            ]);
        }else{
            if ($comment->delete()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success delete comment',
                ]);
            }
        }
    }
}
