<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\ProductCategory;
use App\Models\Restoran;
use App\Models\RestoranReview;
use App\Models\RestoranReviewImage;
use App\Models\TypeFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeRestoController extends Controller
{

    public function getAllRaw(){
        return Restoran::all();
    }

    public function getAllFoodCategoryOnResto($id){
        $obj = FoodCategory::where("resto_id",'=',"$id")->get();
        return $obj;
    }

    public function getAllFoodOnResto($id){
        $obj = Food::where("restoran_id",'=',$id)->get();
        return $obj;
    }

    public function getCertif()
    {
        $datas = Certification::all();
        return $datas;
    }

    public function getFoodRestaurantByCategory($id){
        return Food::where("category_id",'=',$id)->get();
    }

    public function getFoodType(){
        $datas = TypeFood::all();
        return $datas;
    }

    public function getBasedCertif(Request $request,$id){
        return Restoran::where("certification_id","=",$id)->paginate();
    }

    public function storeRestaurantCategory(Request $request,$id){
        $categoryName = $request->name;

        $obj = new FoodCategory();
        $obj->resto_id = $id;
        $obj->name = $categoryName;

        if ($obj->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success',
                'data' => $obj
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed',
                'data' => null
            ],400);
        }
    }

    public function getNearestRestaurant(Request $request){
        $latitude = $request->lat;
        $longitude = $request->long;

        $nearest = Restoran::select(DB::raw('*'))
        // ->orderBy(DB::raw("3959 * acos( cos( radians({$latitude}) ) * cos( radians( lat ) ) * cos( radians( long ) - radians(-{$longitude}) ) + sin( radians({$latitude}) ) * sin(radians(lat)) )"), 'ASC')
        ->get();

        return $nearest;
    }

    public function getDetailRestaurant($id){
        $restoId = $id;
        $restoran = Restoran::find($id);

        $restoReview = RestoranReview::where('restoran_id', $restoId)->get();

        $totReview = RestoranReview::where('restoran_id', $restoId)->count();
        $rating1 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 1)->get()->count();
        $rating2= RestoranReview::where('restoran_id', $restoId)->where('rating_id', 2)->get()->count();
        $rating3 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 3)->get()->count();
        $rating4 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 4)->get()->count();
        $rating5 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 5)->get()->count();
        $sum = ($rating1+$rating2+$rating3+$rating4+$rating5)/5;
        $totalRatings = ((1.0*$rating1)+(2.0*$rating2)+(3.0*$rating3)+(4.0*$rating4)+(5.0*$rating5));

        $ratingCounts = $totReview;
        $avg=0;
        if($totalRatings!=0){
            $avg = $totalRatings/$ratingCounts;
        }

        $photos = $this->getRestoPhotos($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get detail restoran',
                'data' => [
                    'detailResto' => $restoran,
                    'totalReview' => $totReview,
                    'totalRating' => $sum,
                    'rating' => $avg,
                    'photos' => $photos,
                ]
            ]);
        }
    }


    public function getRestoPhotos($restoId)
    {
        $resto = Restoran::where('id', $restoId)->first();
        $restoReview = RestoranReview::where('restoran_id', $resto->id)->get();

        if ($resto == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran review not found',
                'data' => null
            ]);
        }

        $arrPath = array();
        array_push($arrPath, url(''). $resto->image);
        foreach($restoReview as $item)
        {
            $restoPhotos = RestoranReviewImage::where('restoran_review_id', $item->id)->get();
            foreach($restoPhotos as $img)
            {
                array_push($arrPath, url('').'/'. $img->path);
            }
        }

        return $arrPath;
    }

}
