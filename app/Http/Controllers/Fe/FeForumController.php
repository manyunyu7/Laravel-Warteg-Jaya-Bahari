<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumCategory;
use Illuminate\Http\Request;
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

}
