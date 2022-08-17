<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\ForumLike;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    
    public function index()
    {
        $forums = Forum::all();

        if ($forums->count() > 0) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get all forum data',
                'data' => $forums
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get all forum data',
                'data' => null
            ],400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'category_id' => 'required|integer', Rule::in([1, 2, 3, 4,5,6]),
            'title' => 'required|string|min:4',
            'body' => 'required|string|min:3|max:1000',
            'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'category_id.required' => 'category_id cannot be empty',
            'title.required' => 'title cannot be empty',
            'body.required' => 'body cannot be empty',
            'img.image' => 'Image must be and image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $forum = new Forum();
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $ekstension = $img->getClientOriginalExtension();
            $name = 'Forum'.'_'.Auth::user()->name."_".uniqid().'.'.$ekstension;
            if ($request->img->move(public_path('storage/'), $name)) {
                $forum->user_id = Auth::id();
                $forum->category_id = $request->category_id;
                $forum->title = $request->title;
                $forum->body = $request->body;
                $forum->img = $name;

                if ($forum->save()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'Success store forum',
                        'data' => $forum,
                    ],200);
                }else{
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => 'Failed store forum',
                        'data' => null,
                    ],400);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed upload image',
                    'data' => null,
                ],400);
            }
        }
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
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed store forum',
                'data' => null,
            ],400);
        }
    }

    
    public function show($forumId)
    {
        $forum = Forum::find($forumId);

        if ($forum == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'forum not found',
                'data' => null
            ],404);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success get detail forum',
                'data' => $forum
            ],200);
        }
    }

    public function update(Request $request, $forumId)
    {
        $validator = Validator::make($request->all(),
        [
            'category_id' => 'integer', Rule::in([1, 2, 3, 4,5,6]),
            'title' => 'string|min:4',
            'body' => 'string|min:3|max:1000',
            'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'category_id.integer' => 'category_id must be a number',
            'title.string' => 'title must be a string',
            'body.string' => 'body must be a string',
            'img.image' => 'Image must be and image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $forum = Forum::find($forumId);
        $forum->user_id = Auth::id();
        $forum->category_id = $request->category_id;
        $forum->title = $request->title;
        $forum->body = $request->body;

        if ($request->hasFile('img')) {
            $oldImage = public_path($forum->img);

            if (file_exists($oldImage)) {
                try {
                    unlink($oldImage);
                } catch (Exception $e) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e
                    ],400);
                }
            }

            $img = $request->file('img');
            $ekstension = $img->getClientOriginalExtension();
            $name = 'Forum'.'_'.Auth::user()->name."_".uniqid().'.'.$ekstension;
            if ($request->img->move(public_path('storage/'), $name)) {
                $forum->img = $name;

                if ($forum->save()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'Success update forum',
                        'data' => $forum,
                    ],200);
                }else{
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => 'Failed update forum',
                        'data' => null,
                    ],400);
                }
            }
        }

        if ($forum->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success upadate forum',
                'data' => $forum,
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed update forum',
                'data' => null,
            ],400);
        }
    }

    public function destroy($forumId)
    {
        $forum = Forum::find($forumId);
        
        if ($forum == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'forum not found',
            ],404);
        }

        $comments = ForumComment::where('forum_id', $forumId);
        if ($comments != null) {
            try {
                $delComment = $comments->get();
                foreach($delComment as $data)
                {
                    CommentLike::where('comment_id', $data->id)->delete();
                }
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => $e->getMessage()
                ],400);
            }
        }
        $likes = ForumLike::where('forum_id', $forumId);
        

        if ($comments!= null && $likes!= null) {   
            if ($comments->delete() && $likes->delete()) {
                if ($forum->delete()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'forum successfully deleted',
                    ],200);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed to delete comments and likes',
                ],400);
            }
        }else{
            if ($forum->delete()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'forum successfully deleted',
                ],200);
            }
        }

        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => 'failed to delete forums',
        ],400);

    }

    public function likeForum($forumId)
    {
        $forum = Forum::find($forumId);

        if ($forum == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'forum not found',
            ],404);
        }

        
        if (ForumLike::where('user_id', Auth::user()->id)->exists() && ForumLike::where('forum_id', $forumId)->exists()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'you have already like forum',
            ],400);
        }

        $forumLike = new ForumLike();
        $forumLike->user_id = Auth::user()->id;
        $forumLike->forum_id = $forumId;

        if ($forumLike->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success like forum',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed like forum',
            ],400);
        }
    }
}
