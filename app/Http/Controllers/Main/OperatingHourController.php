<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\RestoranOperatingHour;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class OperatingHourController extends Controller
{
    public function store(Request $request, $restoId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'day' => 'required|string|min:6|max:255',
                'hour' => 'required|string|min:6|max:255'
            ],
            [
                'day.required' => 'day cannot be empty',
                'hour.required' => 'hour cannot be empty'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $checkResto = Restoran::find($restoId);
        $checkHour = RestoranOperatingHour::where('restorans_id', $restoId)->get()->count();
        
        if ($checkResto == null) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'restoran not found',
                'data' => null
            ],404);
        }

        if($checkHour == 7)
        {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'operating hour cannot be more than 7 data',
                'data' => null
            ],400);
        }

        $operatingHour = new RestoranOperatingHour();
        $operatingHour->restorans_id = $restoId;
        $operatingHour->day = $request->day;
        $operatingHour->hour = $request->hour;

        if ($operatingHour->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success store restoran operating hour data',
                'data' => $operatingHour
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed store restoran operating hour data',
                'data' => null
            ,400]);
        }
    }
}
