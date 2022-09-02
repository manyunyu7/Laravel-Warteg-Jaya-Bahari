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

    function resetUserPassword(Request $request, $id)
    {
        $newPassword = $request->new_password;
        $user = User::findOrFail($id);
        $user->password = bcrypt($newPassword);
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed',
            ], 400);
        }
    }

    function getUserProfile($id)
    {
        return User::findOrFail($id);
    }

}
