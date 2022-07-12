<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MasjidController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|between:6,100',
                'type_id' => 'required',
                'facilities' => 'required|min:3|max:100',
                'phone' => 'required|string|min:11',
                'operating_start' => 'string|min:4',
                'operating_end' =>'string|min:4',
                'address' => 'required|string|min:10|max:100',
                'lat' => 'required|between:-90,90',
                'long' => 'required|between:-180,180',
                'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'name.required' => 'name cannot be empty',
                'type.id.required' => 'type_id cannot be empty',
                'facilities.required' => 'facilities cannot be empty',
                'phone.required' => 'phone cannot be empty',
                'operating_start.ming:4' => 'operating_start must 4 or more',
                'operating_end.min:4' => 'operating_end must 4 or more',
                'lat.between' => 'The latitude must be in range between -90 and 90',
                'long.between' => 'The longitude must be in range between -100 and 100',
                'img.image' => 'Image must be and image',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $path = 'uploads/img/masjids/';
            $ekstension = $file->getClientOriginalExtension();
            $name = time() . '_'. '.' . $ekstension;
            $request->img->move(public_path($path), $name);


            // for ($i=1; $i <= 1000 ; $i++) { 
                $masjid = Masjid::create([
                    'name' => $request->name,
                    'type_id' => $request->type_id,
                    'facilities' => $request->facilities,
                    'phone' => '+82 '.$request->phone,
                    'operating_start' => $request->operating_start,
                    'operating_end' => $request->operating_end,
                    'address' => "address ",
                    'lat' => $request->lat,
                    'long' => $request->long,
                    'img' => $path.$name
                ]);
            // }
            

            if (!$masjid) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed store masjid data',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success store masjid',
                    'data' => $masjid
                ]);
            }
        } else {
            $masjid = Masjid::create([
                'name' => $request->name,
                'type_id' => $request->type_id,
                'facilities' => $request->facilities,
                'phone' => '+82 '.$request->phone,
                'operating_start' => $request->operating_start,
                'operating_end' => $request->operating_end,
                'address' => $request->address,
                'lat' => $request->lat,
                'long' => $request->long,
            ]);

            if (!$masjid) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed store masjid data',
                    'data' => null
                ],400);
            } else {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success store masjid',
                    'data' => $masjid
                ]);
            }
        }
    }

    public function show(Request $request)
    {
        $isPaginate = $request->isPaginate === 'true'? true: false;

        if (!$isPaginate) {
            $masjids = Masjid::all();

            if ($masjids == null) {
                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => 'Masjid Not Found',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success get all masjid data',
                'data' => $masjids
            ]);
        } else {
            $perPage = $request->perPage;
            if($perPage==null){
                $perPage==1;
            }
            $page = $request->page;
            $paginate = Masjid::paginate(10,['*'],'page',$page);
            if (!$paginate) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'failed pagination masjid',
                    'data' => null
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'success pagination masjid',
                    'data' => $paginate
                ]);
            }
        }
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

    public function update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string|between:6,100',
                'type_id' => 'required',
                'facilities' => 'min:3|max:100',
                'phone' => 'string|min:11',
                'operating_start' => 'string|min:4',
                'operating_end' =>'string|min:4',
                'address' => 'string|min:10|max:100',
                'lat' => 'between:-90,90',
                'long' => 'between:-180,180',
                'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $masjid = Masjid::findOrFail($id);
        $masjid->name = $request->name;
        $masjid->lat = $request->lat;
        $masjid->long = $request->long;
        $masjid->type_id = $request->type_id;
        $masjid->facilities = $request->facilities;
        $masjid->phone = $request->phone;
        $masjid->operating_start = $request->operating_start;
        $masjid->operating_end = $request->operating_end;
        $masjid->address = $request->address;

        if ($request->hasFile('img')) {
            $path = public_path('uploads/img/masjid/') . $masjid->img;

            if (file_exists($path)) {
                try {
                    unlink($path);
                } catch (Exception $e) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e
                    ]);
                }
            }

            $file = $request->file('img');
            $ekstension = $file->getClientOriginalExtension();
            $name = time() . '_' . $request->name . '.' . $ekstension;
            $request->img->move(public_path('uploads/img/masjids'), $name);

            $masjid->img = $name;
        }

        if ($masjid->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'success update masjid',
                'data' => $masjid
            ]);
        } else {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'failed update masjid',
                'data' => null
            ]);
        }
    }
}
