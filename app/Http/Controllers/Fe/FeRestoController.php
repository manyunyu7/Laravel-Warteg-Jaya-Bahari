<?php

namespace App\Http\Controllers\Fe;

use App\Models\DataBangunanResto;
use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\OrderStatu;
use App\Models\Product;
use App\Models\Restoran;
use App\Models\RestoranReview;
use App\Models\RestoranReviewImage;
use App\Models\TypeFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class FeRestoController extends Controller
{


    public function search(Request $request)
    {
        $perPage = $request->perPage;
        $page = $request->page;

        $name = $request->name;
        $type_food_id = $request->type_food_id;
        $cert = $request->certification_id;

        if ($request->sortBy == "distance") {
            $perPage = 9999;
        }

        $obj =  Restoran::where([
            ['name', 'LIKE', '%' . $name . '%'],
            ['type_food_id', 'LIKE', '%' . $type_food_id . '%'],
            ['certification_id', 'LIKE', '%' . $cert . '%'],
        ])->paginate($perPage, ['*'], 'page', $page);



        return $obj;
    }

    public function getByCategory(Request $request, $id)
    {
        $perPage = $request->perPage;
        $page = $request->page;
        $datas = Product::where("category_id", "=", $id)->paginate($perPage, ['*'], 'page', $page);
        return $datas;
    }


    public function getAllRaw()
    {
        return Restoran::all();
    }

    public function getAllOrderStatus()
    {
        $obj = OrderStatu::all();
        return $obj;
    }

    public function getAllFoodCategoryOnResto($id)
    {
        $obj = FoodCategory::where("resto_id", '=', "$id")->get();
        return $obj;
    }

    public function getAllCert()
    {
        return Certification::all();
    }

    public function myResto()
    {
        $obj = Restoran::where("user_id", "=", Auth::id())->get();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success store masjid',
            'data' => $obj
        ], 200);
    }

    public function getAllFoodOnResto($id)
    {
        $obj = Food::where("restoran_id", '=', $id)->get();
        return $obj;
    }

    public function getCertif()
    {
        $datas = Certification::all();
        return $datas;
    }

    public function getFoodRestaurantByCategory($id)
    {
        return Food::where("category_id", '=', $id)->get();
    }
    public function updateRestoCert(Request $request, $id)
    {
        $resto = Restoran::findOrFail($id);
        $resto->certification_id = $request->certification_id;

        if ($resto->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update data',
                'data' => $resto
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add review restoran',
                'data' => null
            ], 400);
        }
    }
    public function updateRestoType(Request $request, $id)
    {
        $resto = Restoran::findOrFail($id);
        $resto->type_food_id = $request->type_food_id;

        if ($resto->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update data',
                'data' => $resto
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add review restoran',
                'data' => null
            ], 400);
        }
    }
    public function updateAddress(Request $request, $id)
    {
        $resto = Restoran::findOrFail($id);
        $resto->type_food_id = $request->type_food_id;

        if ($resto->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update data',
                'data' => $resto
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add review restoran',
                'data' => null
            ], 400);
        }
    }

    public function updatePhone(Request $request, $id)
    {
        $resto = Restoran::findOrFail($id);
        $resto->phone_number = $request->phone;

        if ($resto->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update data',
                'data' => $resto
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add review restoran',
                'data' => null
            ], 400);
        }
    }

    public function getFoodType()
    {
        $datas = TypeFood::all();
        return $datas;
    }

    public function getBasedCertif(Request $request, $id)
    {
        return Restoran::where("certification_id", "=", $id)->paginate();
    }

    public function getReviews(Request $request, $restoId)
    {
        $page = $request->page;
        $perPage = $request->perPage;
        $object = new \stdClass();
        $masjidReviews = RestoranReview::where("restoran_id", '=', $restoId)->paginate($perPage, ['*'], 'page', $page);

        $AllReviews = RestoranReview::where("restoran_id", '=', $restoId)->get();
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

    public function storeRestaurantCategory(Request $request, $id)
    {
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
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed',
                'data' => null
            ], 400);
        }
    }

    public function getNearestRestaurant(Request $request)
    {
        $latitude = $request->lat;
        $longitude = $request->long;

        $nearest = Restoran::select(DB::raw('*'))
            // ->orderBy(DB::raw("3959 * acos( cos( radians({$latitude}) ) * cos( radians( lat ) ) * cos( radians( long ) - radians(-{$longitude}) ) + sin( radians({$latitude}) ) * sin(radians(lat)) )"), 'ASC')
            ->get();

        return $nearest;
    }

    public function getDetailRestaurant($id)
    {
        $restoId = $id;
        $restoran = Restoran::find($id);

        $restoReview = RestoranReview::where('restoran_id', $restoId)->get();

        $totReview = RestoranReview::where('restoran_id', $restoId)->count();
        $rating1 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 1)->get()->count();
        $rating2 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 2)->get()->count();
        $rating3 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 3)->get()->count();
        $rating4 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 4)->get()->count();
        $rating5 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 5)->get()->count();
        $sum = ($rating1 + $rating2 + $rating3 + $rating4 + $rating5) / 5;
        $totalRatings = ((1.0 * $rating1) + (2.0 * $rating2) + (3.0 * $rating3) + (4.0 * $rating4) + (5.0 * $rating5));

        $ratingCounts = $totReview;
        $avg = 0;
        if ($totalRatings != 0) {
            $avg = $totalRatings / $ratingCounts;
        }

        $photos = $this->getRestoPhotos($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ]);
        } else {
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
        array_push($arrPath, $resto->img_full_path);
        foreach ($restoReview as $item) {
            $restoPhotos = RestoranReviewImage::where('restoran_review_id', $item->id)->get();
            foreach ($restoPhotos as $img) {
                array_push($arrPath, url('') . '/' . $img->path);
            }
        }

        return $arrPath;
    }


    public function updateLtLb(Request $request, $id)
    {
        $resto_id = $id; // Provide the specific resto_id value you want to check
        $existingRecord = DataBangunanResto::where('resto_id', $resto_id)->first();

        if ($existingRecord) {
            // Update the existing record
        } else {
            $existingRecord = new DataBangunanResto();
        }

        // Retrieve the request input for the specified fields
        $panjang_bangunan = $request->input('panjang_bangunan', '');
        $lebar_bangunan = $request->input('lebar_bangunan', '');
        $panjang_tanah = $request->input('panjang_tanah', '');
        $lebar_tanah = $request->input('lebar_tanah', '');
        $jumlah_lantai = $request->input('jumlah_lantai', '');
        $bisaDimajukan = $request->input('bisa_dimajukan', '');
        $ijinDomisili = $request->input('ijin_domisili', '');
        $peruntukanBangunan = $request->input('peruntukan_bangunan', '');


        $existingRecord->resto_id = $resto_id;
        $existingRecord->panjang_bangunan = $panjang_bangunan;
        $existingRecord->lebar_bangunan = $lebar_bangunan;
        $existingRecord->peruntukan_bangunan = $peruntukanBangunan;
        $existingRecord->panjang_tanah = $panjang_tanah;
        $existingRecord->lebar_tanah = $lebar_tanah;
        $existingRecord->jumlah_lantai = $jumlah_lantai;
        $existingRecord->ijin_domisili = $ijinDomisili;
        $existingRecord->bisa_dimajukan = $bisaDimajukan;

        if ($existingRecord->save()) {
            return response()->json([
                'message' => 'Data saved successfully',
                'status' => true,
                'data' => $existingRecord
            ]);
        } else {
            return response()->json([
                'message' => 'Data saved successfully',
                'status' => false
            ]);
        }
    }

    public function updateLalinParkir(Request $request, $id)
    {
        $resto_id = $id; // Provide the specific resto_id value you want to check
        $existingRecord = DataBangunanResto::where('resto_id', $resto_id)->first();

        if ($existingRecord) {
            // Update the existing record
        } else {
            $existingRecord = new DataBangunanResto();
        }


        $existingRecord->resto_id = $resto_id;
        $existingRecord->{'5_menit_mobil'} = $request->input('5_menit_mobil', '');
        $existingRecord->{'5_menit_motor'} = $request->input('5_menit_motor', '');
        $existingRecord->{'5_menit_truk'} = $request->input('5_menit_truk', '');
        $existingRecord->{'parkir_motor'} = $request->input('parkir_motor', '');
        $existingRecord->{'parkir_mobil'} = $request->input('parkir_mobil', '');
        $existingRecord->{'jenis_jalan'} = $request->input('jenis_jalan', '');
        $existingRecord->{'rencana_pelebaran'} = $request->input('rencana_pelebaran', '');

        if ($existingRecord->save()) {
            return response()->json([
                'message' => 'Data Lalin saved successfully',
                'status' => true,
                'data' => $existingRecord
            ]);
        } else {
            return response()->json([
                'message' => 'Data Lalin saved successfully',
                'status' => false
            ]);
        }
    }


    public function updateListrikAir(Request $request, $id)
    {
        $resto_id = $id; // Provide the specific resto_id value you want to check
        $existingRecord = DataBangunanResto::where('resto_id', $resto_id)->first();

        if ($existingRecord) {
            // Update the existing record
        } else {
            $existingRecord = new DataBangunanResto();
        }

        $existingRecord->resto_id = $resto_id;
        $existingRecord->{'fasilitas_listrik_watt'} = $request->input('fasilitas_listrik_watt', '0');
        $existingRecord->{'fasilitas_air'} = $request->input('fasilitas_air', '');
        $existingRecord->{'saluran_air'} = $request->input('saluran_air', '');

        if ($existingRecord->save()) {
            return response()->json([
                'message' => 'Data Listrik Air saved successfully',
                'status' => true,
                'data' => $existingRecord
            ]);
        } else {
            return response()->json([
                'message' => 'Data Listrik Air saved error',
                'status' => false
            ]);
        }
    }

    public function updateLegal(Request $request, $id)
    {
        $resto_id = $id;
        $existingRecord = DataBangunanResto::where('resto_id', $resto_id)->first();

        if ($existingRecord) {
            // Update the existing record
        } else {
            $existingRecord = new DataBangunanResto();
        }

        $existingRecord->resto_id = $resto_id;
        $existingRecord->{'nama_pemilik_sertifikat'} = $request->input('nama_pemilik_sertifikat', '');
        $existingRecord->{'jenis_sertifikat'} = $request->input('jenis_sertifikat', '');
        $existingRecord->{'jenis_pemilik_sertifikat'} = $request->input('jenis_pemilik_sertifikat', '');
        $existingRecord->{'is_sewa'} = $request->input('is_sewa', '');
        $existingRecord->{'harga_sewa'} = $request->input('harga_sewa', '');
        $existingRecord->{'masa_berlaku_sertifikat'} = $request->input('masa_berlaku_sertifikat', '');

        if ($existingRecord->save()) {
            return response()->json([
                'message' => 'Data Legal saved successfully',
                'status' => true,
                'data' => $existingRecord
            ]);
        } else {
            return response()->json([
                'message' => 'Data Legal saved error',
                'status' => false
            ]);
        }
    }

}
