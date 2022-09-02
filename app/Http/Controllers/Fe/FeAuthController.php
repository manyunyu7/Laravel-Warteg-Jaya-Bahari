<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\ForumComment;
use App\Models\ForumLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class FeAuthController extends Controller
{

    function getUserProfile($id)
    {
        return User::findOrFail($id);
    }

}
