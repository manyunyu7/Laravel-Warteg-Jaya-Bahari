<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\MasjidReviewImage;
use App\Models\Restoran;
use App\Models\RestoranOperatingHour;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatingHourController extends Controller
{
    public function store(Request $request, $restoId)
    {
//        $validator = Validator::make(
//            $request->all(),
//            [
//                'day' => 'required|string|min:6|max:255',
//                'hour' => 'required|string|min:6|max:255'
//            ],
//            [
//                'day.required' => 'day cannot be empty',
//                'hour.required' => 'hour cannot be empty'
//            ]
//        );

//        if ($validator->fails()) {
//            return response()->json($validator->errors()->toJson(), 400);
//        }

        $obj = RestoranOperatingHour::where("restorans_id", $restoId)->get();

        foreach ($obj as $item) {
            if ($item->day_code == $request->day_code) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Day Already Exist',
                    'data' => null], 400);
            }
        }


        $checkResto = Restoran::find($restoId);
        $checkHour = RestoranOperatingHour::where('restorans_id', $restoId)->get()->count();

        if ($checkResto == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ], 404);
        }

        if ($checkHour == 7) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'operating hour cannot be more than 7 data',
                'data' => null
            ], 400);
        }

        $operatingHour = new RestoranOperatingHour();
        $operatingHour->restorans_id = $restoId;
        $operatingHour->day = $request->day;
        $operatingHour->hour = $request->hour;
        $operatingHour->day_code = $request->day_code;
        $operatingHour->hour_start = $request->hour_start;
        $operatingHour->hour_end = $request->hour_end;


        if ($operatingHour->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success store restoran operating hour data',
                'data' => $operatingHour
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed store restoran operating hour data',
                'data' => null
                , 400]);
        }
    }

    public function getByResto($restoId)
    {
        $operatingHour = RestoranOperatingHour::where('restorans_id', $restoId)->get();

        if (!$operatingHour) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'operating hour not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get operating hour',
            'data' => $operatingHour
        ], 200);
    }

    public function getDetail($hourId)
    {
        $operatingHour = RestoranOperatingHour::find($hourId);

        if (!$operatingHour) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'operating hour not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get operating hour',
            'data' => $operatingHour
        ], 200);
    }

    public function editOperatingHour(Request $request, $restoId, $hourId)
    {
//        $validator = Validator::make(
//            $request->all(),
//            [
//                'day' => 'required|string|min:6|max:255',
//                'hour' => 'required|string|min:6|max:255'
//            ],
//            [
//                'day.required' => 'day cannot be empty',
//                'hour.required' => 'hour cannot be empty'
//            ]
//        );

//        if ($validator->fails()) {
//            return response()->json($validator->errors()->toJson(), 400);
//        }

        $checkResto = Restoran::find($restoId);

        if (!$checkResto) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ], 404);
        }

        $cekOperatingHour = RestoranOperatingHour::find($hourId);

        if (!$cekOperatingHour) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'operating hour not found',
                'data' => null
            ], 404);
        }

        $cekOperatingHour->restorans_id = $restoId;
        $cekOperatingHour->day = $request->day;
        $cekOperatingHour->hour = $request->hour;
        $cekOperatingHour->day_code = $request->day_code;
        $cekOperatingHour->hour_start = $request->hour_start;
        $cekOperatingHour->hour_end = $request->hour_end;


        if ($cekOperatingHour->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update restoran operating hour data',
                'data' => $cekOperatingHour
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed update restoran operating hour data',
                'data' => null
                , 400]);
        }

    }

    public function deleteOperatingHour($restoId, $hourId)
    {
        $userId = Auth::id();
        $restoran = Restoran::find($restoId);
        $cekOperatingHour = RestoranOperatingHour::find($hourId);

        if (!$cekOperatingHour) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'operating hour not found',
                'data' => null
            ], 404);
        }

        if ($cekOperatingHour->restorans_id != $restoId) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Not operating hour restoran',
                'data' => null
            ], 400);
        }

//        if ($restoran->user_id != $userId) {
//            return response()->json([
//                'success' => false,
//                'code' => 400,
//                'message' => 'Access Denied',
//                'data' => null
//            ],400);
//        }

        if ($cekOperatingHour->delete()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Sucessfully deleted operating hour',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed deleted operating hour',
            ], 400);
        }
    }
}
