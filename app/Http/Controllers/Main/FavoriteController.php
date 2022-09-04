<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\FavoriteMasjid;
use App\Models\FavoriteRestoran;
use App\Models\Masjid;
use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function addResto($restoId)
    {
        $resto_id = Restoran::where('id', $restoId)->first();
        $favorite = new FavoriteRestoran();
        $user = Auth::id();

        if ($resto_id == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found'
            ],404);
        }

        $checkFav = FavoriteRestoran::where([
            "user_id" => $user,
            "restorans_id" => $restoId
        ])->first();

        if ($checkFav) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'You already like this restoran'
            ],400);
        }

        $favorite->user_id = $user;
        $favorite->restorans_id = $resto_id->id;

        if ($favorite->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add favorite restoran',
                'data' => $favorite
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add favorite restoran',
                'data' => null
            ],400);
        }
    }

    public function addMasjid($masjidId)
    {
        $masjid_id = Masjid::where('id', $masjidId)->first();
        $favorite = new FavoriteMasjid();
        $user = Auth::id();

        if ($masjid_id == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'masjid not found'
            ],404);
        }

        $checkFav = FavoriteMasjid::where([
            "user_id" => $user,
            "masjid_id" => $masjidId
        ])->first();

        if ($checkFav) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'You already like this masjid'
            ],400);
        }

        $favorite->user_id = $user;
        $favorite->masjid_id = $masjid_id->id;

        if ($favorite->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add favorite masjid',
                'data' => $favorite
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add favorite masjid',
                'data' => null
            ],400);
        }
    }

    public function getRestoFavorites()
    {
        $user = Auth::id();

        $favorites = FavoriteRestoran::where('user_id', $user)->get();

        if (!$favorites) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Favorite restoran not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get favorite restoran',
            'data' => $favorites
        ],200);
    }

    public function getMasjidFavorites()
    {

        $user = Auth::id();

        $favorites = FavoriteMasjid::where('user_id', $user)->get();

        if (!$favorites) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Favorite masjid not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get favorite masjid',
            'data' => $favorites
        ],200);
    }

    public function deleteResto($favId)
    {
        $resto =   $checkFav = FavoriteRestoran::where([
            "user_id" => Auth::id(),
            "restorans_id" => $favId
        ])->first();

        if ($resto == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran fav not found'
            ],404);
        }

        if ($resto->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete restoran fav'
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete restoran fav'
            ],400);
        }
    }

    public function deleteMasjid($favId)
    {
        $masjid =  FavoriteMasjid::where([
            "user_id" => Auth::id(),
            "masjid_id" => $favId
        ])->first();

        if ($masjid == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'masjid fav not found'
            ],404);
        }

        if ($masjid->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete masjid fav'
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete masjid fav'
            ],400);
        }
    }
}
