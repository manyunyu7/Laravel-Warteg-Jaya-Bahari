<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\ForumComment;
use App\Models\ForumLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class FeForumController extends Controller
{

    function getForumCategory()
    {
        return $data = ForumCategory::all();
    }

    public function getForumPaginate(Request $request)
    {
        $page = $request->page;
        $perPage = $request->perPage;
        $forums = Forum::with(["user", "category", "comments", "likes"])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
        return $forums;
    }


    public function getComment($forumId)
    {
        $data = ForumComment::with(["user", "likes"])->where("forum_id", '=', $forumId)->get();
        return $data;
    }

    public function unlikeForum($forumId)
    {
        $data = ForumLike::where([
            'user_id' => Auth::id(),
            'forum_id' => $forumId
        ]);
        if ($data->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success unlike forum',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed unlike forum',
            ], 400);
        }
    }

    public function likeForum($forumId)
    {
        $forum = Forum::find($forumId);

        if ($forum == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'forum not found',
            ], 404);
        }

        $data = ForumLike::where([
            'user_id' => Auth::id(),
            'forum_id' => $forumId
        ]);

        if ($data->exists()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'you have already like forum',
            ], 400);
        }

        $forumLike = new ForumLike();
        $forumLike->user_id = Auth::user()->id;
        $forumLike->forum_id = $forumId;

        if ($forumLike->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success like forum',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed like forum',
            ], 400);
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

        $data = CommentLike::where([
            'user_id' => Auth::id(),
            'comment_id' => $commendId
        ]);

        if ($data->exists()) {
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

    public function unlikeComment($commentId){
        $data = CommentLike::where([
            'user_id' => Auth::id(),
            'comment_id' => $commentId
        ]);
        if ($data->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success unlike forum',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed unlike forum',
            ],400);
        }
    }

}
