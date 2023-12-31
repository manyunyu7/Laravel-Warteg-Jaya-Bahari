<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use App\Models\MasjidReview;
use App\Models\MasjidReviewImage;
use App\Models\MasjidType;
use App\Models\Restoran;
use Illuminate\Http\Request;
use stdClass;

class FeMasjidController extends Controller
{

    public function search(Request $request)
    {
        $perPage = $request->perPage;
        $page = $request->page;

        $name = $request->name;
        $type_id = $request->type_id;
        $cert = $request->certification_id;

        if ($request->sortBy == "distance") {
            $perPage = 9999;
        }

        $obj = Masjid::where([
            ['name', 'LIKE', '%' . $name . '%'],
            ['type_id', 'LIKE', '%' . $type_id . '%'],
        ])->paginate($perPage, ['*'], 'page', $page);
        return $obj;
    }

    public function getMasjidType()
    {
        return MasjidType::all();
    }

    public function getMasjidPhoto($id)
    {
        $masjid = Masjid::where("id", '=', $id)->first();
        $masjidReviews = MasjidReview::where("masjid_id", '=', $id)->get();
        $arrayPhotoUrl = array();
        array_push($arrayPhotoUrl, url("/") . "/storage/" . $masjid->img);
        foreach ($masjidReviews as $item) {
            $masjidPhotos = MasjidReviewImage::where("masjid_review_id", '=', $item->id)->get();
            foreach ($masjidPhotos as $itemPhoto) {
                array_push($arrayPhotoUrl, url('/') . "/" . $itemPhoto->path);
            }
        }
        return $arrayPhotoUrl;
    }

    public function getMasjidReviews(Request $request, $masjidId)
    {
        $page = $request->page;
        $perPage = $request->perPage;
        $object = new stdClass();
        $masjidReviews = MasjidReview::where("masjid_id", '=', $masjidId)->paginate($perPage, ['*'], 'page', $page);

        $AllReviews = MasjidReview::where("masjid_id", '=', $masjidId)->get();
        $reviewCount = $this->getReviewCount($AllReviews);
        $object->reviews = $masjidReviews;
        $object->review_count = $reviewCount;
        return $object;
    }

    public function getReviewCount($datas)
    {
        $object = new stdClass();

        $ratings1 = 0;
        $ratings2 = 0;
        $ratings3 = 0;
        $ratings4 = 0;
        $ratings5 = 0;

        $avg = 0;

        foreach ($datas as $data) {
            if ($data->rating_id == 1) {
                $ratings1 += 1;
            }
            if ($data->rating_id == 2) {
                $ratings2 += 1;
            }
            if ($data->rating_id == 3) {
                $ratings3 += 1;
            }
            if ($data->rating_id == 4) {
                $ratings4 += 1;
            }
            if ($data->rating_id == 5) {
                $ratings5 += 1;
            }
        }


        $totalRatings = ((1.0 * $ratings1) + (2.0 * $ratings2) + (3.0 * $ratings3) + (4.0 * $ratings4) + (5.0 * $ratings5));
        $ratingCounts = $datas->count();
        $avg = 0;

        if ($totalRatings != 0) {
            $avg = $totalRatings / $ratingCounts;
        }


        $object->avg = round($avg);
        $object->rating1 = $ratings1;
        $object->rating2 = $ratings2;
        $object->rating3 = $ratings3;
        $object->rating4 = $ratings4;
        $object->rating5 = $ratings5;


        return $object;
    }

    public function index($id)
    {

        $masjid = Masjid::where('id', $id)->get();

        if (!$masjid) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Masjid Not Found',
                'data' => null
            ]);
        } else {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get detail masjid',
                'data' => $masjid
            ]);
        }
    }
}
