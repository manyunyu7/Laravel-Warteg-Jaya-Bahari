<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\TypeFood;
use App\Models\UserFavorite;
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
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success get restorans data', 
                    'data' => $restorans
                ]);
            }
        }else{
            $paginate = DB::table('restorans')->orderBy('name', 'asc')->paginate(4);

            if (!$paginate) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed paginate restorans data',
                    'data' => null
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success paginate restorans data',
                    'data' => $paginate
                ]);
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sortByFoodType(Request $request)
    {
        $restoByTypeFood = DB::table('restorans')->where('type_food_id', $request->type_food_id)->orderBy('name', 'asc')->get();

        if ($restoByTypeFood == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ]);
        }

        if (!$restoByTypeFood) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get restorans data by type of food',
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get restorans data by type of food',
                'data' => $restoByTypeFood
            ]);
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
            ]);
        }

        if (!$restoByCertifciation) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed get restorans data by certification',
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get restorans data by certification',
                'data' => $restoByCertifciation
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            "name" => 'required|string|min:6|max:255',
            "type_food_id" => "required|string", Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            "certification_id" => "required|string", Rule::in([1,2,3,4]),
            "description" => "required|string|min:10|max:600 ",
            "address" => "required|string|min:10|max:600 ",
            "operating_hour" => "required|string|min:5",
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
            "operating_hour.required" => "operating_hour cannot be empty",
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

        $restoran = new Restoran();
        $restoran->name = $request->name;
        $restoran->type_food_id = $request->type_food_id;
        $restoran->certification_id = $request->certification_id;
        $restoran->description = $request->description;
        $restoran->address = $request->address;
        $restoran->operating_hour = $request->operating_hour;
        $restoran->phone_number = $request->phone_number;
        $restoran->lat = $request->lat;
        $restoran->long = $request->long;

        $file = $request->file('image');
        $ekstension = $file->getClientOriginalExtension();
        $name = time().'_'.$restoran->name.'.'.$ekstension;
        $request->image->move(public_path('uploads/resto'),$name);

        $restoran->image = $name;
        $restoran->is_visible = $restoran->is_visible === "1" ? true : false;
        

        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success add restoran', 
                'data' => $restoran
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed add restoran', 
                'data' => null
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($restoId)
    {
        $restoran = Restoran::find($restoId);

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
                'data' => $restoran
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTypeFood()
    {
        $food = TypeFood::all();

        if($food == null){
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'food type not found', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get type food data', 
                'data' => $food
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $restoId)
    {
        $validator = Validator::make($request->all(),
        [
            "name" => 'required|string|min:6|max:255',
            "type_food_id" => "required|string", Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            "certification_id" => "required|string", Rule::in([1,2,3,4]),
            "description" => "required|string|min:10|max:600 ",
            "address" => "required|string|min:10|max:600 ",
            "operating_hour" => "required|string|min:5",
            "phone_number" => "required|string|min:11",
            "lat" => 'required|between:-90,90',
            "long" => 'required|between:-180,180',
            "image" => 'mimes:jpeg,png,jpg,gif,svg|max:12048',
            "is_visible" => 'required|boolean'
        ],
        [
            "name.required" => "name cannot be empty",
            "type_food_id. required" => "type food cannot be empty",
            "certification_id.required" => "certification cannot be empty",
            "description.required" => "description cannot be empty",
            "address.required" => "address cannot be empty",
            "operating_hour.required" => "operating_hour cannot be empty",
            "phone_number.required" => "phone_number cannot be empty",
            "lat.required" => "latitude cannot be empty",
            "long.required" => "longitude cannot be empty",
            "image.image" => "Image must be an image",
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
            ]);
        }

        $restoran->name = $request->name;
        $restoran->type_food_id = $request->type_food_id;
        $restoran->certification_id = $request->certification_id;
        $restoran->description = $request->description;
        $restoran->address = $request->address;
        $restoran->operating_hour = $request->operating_hour;
        $restoran->phone_number = $request->phone_number;
        $restoran->lat = $request->lat;
        $restoran->long = $request->long;

        if ($request->hasFile('image')) {
            $path = public_path('uploads/img/resto/').$restoran->image;

            if (file_exists($path)) {
                try{
                    unlink($path);
                } catch(Exception $e){
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
            $file = $request->file('image');
            $ekstension = $file->getClientOriginalExtension();
            $name = time().'_'.$restoran->name.'.'.$ekstension;
            $request->image->move(public_path('uploads/resto'),$name);
            $restoran->image = $name;
        }

        
        $restoran->is_visible = $restoran->is_visible === "1" ? true : false;

        if ($restoran->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran data', 
                'data' => $restoran
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update restoran data', 
                'data' => null
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($restoId)
    {
        $restoran = Restoran::find($restoId);

        if ($restoran == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found'
            ]);
        }

        if ($restoran->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success delete restoran'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed delete restoran', 
            ]);
        }
    }
}
