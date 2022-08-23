<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class ForumCommentController extends Controller
{
    public function index($forumId)
    {
        $comments = ForumComment::where('forum_id', $forumId)->get();

        if ($comments == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Comments not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get all forum comments',
            'data' => $comments
        ],200);
    }

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
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed to add comment',
                'data' => null
            ],400);
        }
    }

    public function show($commentId)
    {
        $comment = ForumComment::find($commentId);

        if ($comment == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Comment not found',
                'data' => null
            ],404);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success get detail comment',
                'data' => $comment
            ],200);
        }
    }

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
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed update comment',
                'data' => null
            ],400);
        }
    }

    public function destroy($commentId)
    {
        $comment = ForumComment::where('id', $commentId)->first();

        if ($comment == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'comment not found',
            ],404);
        }

        try {
            $commentLikes = CommentLike::where('comment_id', $comment->id)->get();
            
            if ($commentLikes != null) {
                if ($commentLikes->delete()) {
                    if ($comment->delete()) {
                        return response()->json([
                            'success' => true,
                            'code' => 200,
                            'message' => 'success delete comment',
                        ],200);
                    }
                }else{
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => 'failed delete comment',
                    ],400);
                }
            }else{
                if ($comment->delete()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'success delete comment',
                    ],200);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $e->getMessage()
            ],400);
        }
    }

    public function likeComment($commendId)
    {
        $comment = ForumComment::find($commendId);

        if ($comment == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'comment not found',
            ],404);
        }

        if (CommentLike::where('user_id', Auth::user()->id)->exists() && CommentLike::where('comment_id', $commendId)->exists()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'you have already like comment',
            ],400);
        }

        $commentLike  = new CommentLike();
        $commentLike->user_id = Auth::user()->id;
        $commentLike->comment_id = $commendId;

        if ($commentLike->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success like comment',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed like forum',
            ],400);
        }
    }

    public function unlikeComment($commentId)
    {
        $like = CommentLike::where([
            'user_id' => Auth::id(),
            'comment_id' => $commentId,
        ]);

        if ($like->count() == 0) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'like not found',
            ],404);
        }

        if ($like->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success unlike comment',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed unlike comment',
            ],400);
        }
    }
}
