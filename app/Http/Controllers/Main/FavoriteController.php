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

    public function getAllFavorite()
    {
        $restorans = DB::table('favorite_restorans')->where('user_id', Auth::id())
                    ->join('restorans', 'favorite_restorans.restorans_id', '=', 'restorans.id')
                    ->select('favorite_restorans.user_id' ,'restorans.name')
                    ->get();
        
        $masjids = DB::table('favorite_masjids')->where('user_id', Auth::id())
                    ->join('masjids', 'favorite_masjids.masjid_id', '=', 'masjids.id')
                    ->select('favorite_masjids.user_id','masjids.name')
                    ->get();
        
        if ($restorans && $masjids == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'favorite data not found', 
                'data' => null
            ],404);
        }
        
        if ($restorans == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restorans favorite data not found', 
                'data' => $masjids
            ],404);
        }

        if ($masjids == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'masjids favorite data not found', 
                'data' => $restorans
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get data restorans', 
            'data' => [
                "Restorans"=> $restorans, 
                "Masjid"=>$masjids
            ]
        ],200);
    }

    public function deleteResto($favId)
    {
        $resto = FavoriteRestoran::find($favId);

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
        $masjid = FavoriteMasjid::find($favId);

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
