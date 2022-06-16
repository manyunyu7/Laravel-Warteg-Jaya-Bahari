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
            ]);
        }

        $favorite->user_id = $user;
        $favorite->restorans_id = $resto_id->id;

        if ($favorite->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add favorite restoran',
                'data' => $favorite
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add favorite restoran', 
                'data' => null
            ]);
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
            ]);
        }

        $favorite->user_id = $user;
        $favorite->masjid_id = $masjid_id->id;

        if ($favorite->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add favorite masjid',
                'data' => $favorite
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add favorite masjid', 
                'data' => null
            ]);
        }
    }

    public function getAllFavorite()
    {
        $user = Auth::id();
        $resto = FavoriteRestoran::find($user);
        $masjid = FavoriteMasjid::find($user);

        dd($resto->restorans_id->restorans->name, $masjid->masjid_id->masjids->name);
    }
}
