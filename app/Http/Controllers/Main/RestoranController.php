<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\OrderStatu;
use App\Models\Restoran;
use App\Models\RestoranReview;
use App\Models\RestoranReviewImage;
use App\Models\TypeFood;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RestoranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isPaginate = $request->isPaginate == 'true'?true:false;

        if (!$isPaginate) {
            $restorans = Restoran::all();
            if (!$restorans) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed get restorans data', 
                    'data' => null
                ],400);
            }else{
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success get restorans data', 
                    'data' => $restorans
                ],200);
            }
        }else{
            $paginate = DB::table('restorans')->orderBy('name', 'asc')->paginate(4);

            if (!$paginate) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed paginate restorans data',
                    'data' => null
                ],400);
            }else{
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success paginate restorans data',
                    'data' => $paginate
                ],200);
            }
        }

    }

    public function sortByFoodType(Request $request)
    {
        $restoByTypeFood = DB::table('restorans')->where('type_food_id', $request->type_food_id)->orderBy('name', 'asc')->get();

        if ($restoByTypeFood == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ],404);
        }

        if (!$restoByTypeFood) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get restorans data by type of food',
                'data' => null
            ],400);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get restorans data by type of food',
                'data' => $restoByTypeFood
            ],200);
        }
    }

    public function sortByCertification(Request $request)
    {
        $restoByCertifciation = DB::table('restorans')->where('certification_id', $request->certification_id)->orderBy('name', 'asc')->get();

        if ($restoByCertifciation == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ],404);
        }

        if (!$restoByCertifciation) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get restorans data by certification',
                'data' => null
            ],400);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get restorans data by certification',
                'data' => $restoByCertifciation
            ],200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            "name" => 'required|string|min:6|max:255',
            "type_food_id" => "required|string", Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            "certification_id" => "required|string", Rule::in([1,2,3,4]),
            "description" => "required|string|min:10|max:600 ",
            "address" => "required|string|min:10|max:600 ",
            "phone_number" => "required|string|min:11",
            "lat" => 'required|between:-90,90',
            "long" => 'required|between:-180,180',
            "image" => 'mimes:jpeg,png,jpg,gif,svg|max:12048|required',
            "is_visible" => 'required|boolean'
        ],
        [
            "name.required" => "name cannot be empty",
            "type_food_id. required" => "type food cannot be empty",
            "certification_id.required" => "certification cannot be empty",
            "description.required" => "description cannot be empty",
            "address.required" => "address cannot be empty",
            "phone_number.required" => "phone_number cannot be empty",
            "lat.required" => "latitude cannot be empty",
            "long.required" => "longitude cannot be empty",
            "image.required" => 'image cannot be empty',
            "image.image" => "Image must be an image",
            "is_visible.required" => "is_visible cannot be empty",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $status = $request->is_visible;
        $restoran = new Restoran();
        $restoran->name = $request->name;
        $restoran->user_id = Auth::id();
        $restoran->type_food_id = $request->type_food_id;
        $restoran->certification_id = $request->certification_id;
        $restoran->description = $request->description;
        $restoran->address = $request->address;
        $restoran->phone_number = $request->phone_number;
        $restoran->lat = $request->lat;
        $restoran->long = $request->long;

        $file = $request->file('image');
        $ekstension = $file->getClientOriginalExtension();
        $name = 'restoran'.'_'.time().'_'.$restoran->name.'.'.$ekstension;
        $request->image->move(public_path('storage'),$name);

        $restoran->image = $name;
        $restoran->is_visible = $status  === "0" ? false : true;
        

        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add restoran', 
                'data' => $restoran
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add restoran', 
                'data' => null
            ],400);
        }
        
    }

    public function show($restoId)
    {
        $restoran = Restoran::find($restoId);
        $totReview = RestoranReview::where('restoran_id', $restoId)->count();
        $rating1 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 1)->get()->count();
        $rating2= RestoranReview::where('restoran_id', $restoId)->where('rating_id', 2)->get()->count();
        $rating3 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 3)->get()->count();
        $rating4 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 4)->get()->count();
        $rating5 = RestoranReview::where('restoran_id', $restoId)->where('rating_id', 5)->get()->count();
        $sum = ($rating1+$rating2+$rating3+$rating4+$rating5)/5;

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get detail restoran', 
                'data' => [
                    'detailResto' => $restoran,
                    'totalReview' => $totReview,
                    'totRating' => $sum,
                ]
            ],200);
        }
    }

    public function getTypeFood()
    {
        $food = TypeFood::all();

        if($food == null){
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'food type not found', 
                'data' => null
            ],404);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get type food data', 
                'data' => $food
            ],200);
        }
    }

    public function editImage(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "image" => 'mimes:jpeg,png,jpg,gif,svg|max:12048',
        ],
        [
            "image.image" => "Image must be an image",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }

        if ($request->hasFile('image')) {
            $path = public_path('storage').$restoran->image;

            if (file_exists($path)) {
                try{
                    unlink($path);
                } catch(Exception $e){
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e->getMessage(),
                    ],400);
                }
            }
            $file = $request->file('image');
            $ekstension = $file->getClientOriginalExtension();
            $name = time().'_'.$restoran->name.'.'.$ekstension;
            $request->image->move(public_path('storage/'),$name);
            $restoran->image = $name;

            if ($restoran->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success update restoran data', 
                    'data' => $restoran
                ],200);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed update restoran data', 
                    'data' => null
                ],400);
            }
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'No file to upload', 
                'data' => null
            ],400);
        }

    }

    public function editCertification(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "certification_id" => "required", Rule::in([1,2,3,4]),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }

        $restoran->certification_id = $request->certification_id;

        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran data', 
                'data' => $restoran
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update restoran data', 
                'data' => null
            ],400);
        }
    }

    public function editType(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "type_food_id" => "required", Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9]),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }

        $restoran->type_food_id = $request->type_food_id;

        
        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran data', 
                'data' => $restoran
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update restoran data', 
                'data' => null
            ],400);
        }
    }

    public function editAddress(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "lat" => 'required|between:-90,90',
            "long" => 'required|between:-180,180',
            "address" => "required|string|min:10|max:600 ",
        ],
        [
            "lat.required" => "latitude cannot be empty",
            "long.required" => "longitude cannot be empty",
            "address.required" => "address cannot be empty",
            "address.string" => "address must be a string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }

        $restoran->address = $request->address;
        $restoran->lat = $request->lat;
        $restoran->long = $request->long;
        
        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran data', 
                'data' => $restoran
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update restoran data', 
                'data' => null
            ],400);
        }
    }

    public function editPhoneNumber(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "phone_number" => "required|string|min:11",
        ],
        [
            "phone_number.required" => "phone_number cannot be empty",
            "phone_number.string" => "phone_number must be a string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }

        $restoran->phone_number = $request->phone_number;
        
        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran data', 
                'data' => $restoran
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update restoran data', 
                'data' => null
            ],400);
        }
    }

    public function editVisibility(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "is_visible" => 'required|boolean'
        ],
        [
            "is_visible.required" => "is_visible cannot be empty",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found', 
                'data' => null
            ],404);
        }

        $restoran->is_visible = $request->is_visible === "1" ? true : false;

        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran data', 
                'data' => $restoran
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update restoran data', 
                'data' => null
            ],400);
        }
    }
    
    public function destroy($restoId)
    {
        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found'
            ],404);
        }

        if ($restoran->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete restoran'
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete restoran', 
            ],400);
        }
    }

    public function getRestoPhotos($restoId, Request $request)
    {
        $resto = Restoran::where('id', $restoId)->first();
        $restoReview = RestoranReview::where('restoran_id', $resto->id)->get();

        if ($resto == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran review not found',
                'data' => null
            ],404);
        }

        $arrPath = array();
        array_push($arrPath, url('/').'/'. $resto->iamge);
        foreach($restoReview as $item)
        {
            $restoPhotos = RestoranReviewImage::where('restoran_review_id', $item->id)->get();
            foreach($restoPhotos as $img)
            {
                array_push($arrPath, url('/').'/'. $img->path);
            }
        }

        if (!$arrPath) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get restoran photos',
                'data' => null
            ],400);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get restoran photos',
                'data' => $arrPath
            ],200);
        }
    }

    public function getRestoByOwner()
    {
        $owner = Auth::id();
        $restoran = Restoran::where('user_id', $owner)->get();

        if (!$restoran) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get restorans',
            'data' => $restoran
        ],200);
    }

    public function getRestoDetailByOwner($restoId)
    {
        $owner = Auth::id();
        $restoran = Restoran::where(
            [
                'id' => $restoId,
                'user_id' => $owner
            ]
        )->first();

        if (!$restoran) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get restorans',
            'data' => $restoran
        ],200);
    }

    public function getAllOrderStatus()
    {
        $status = OrderStatu::all();

        if ($status == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Order status not found',
                'data' => null
            ],404);
        }

        if (!$status) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed get order status data',
                'data' => null
            ],400);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success get order status data',
                'data' => $status
            ],200);
        }
    }
}
