<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\OrderCart;
use App\Models\Restoran;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function registerDriver(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6',
                'phone_number' => 'required|string|min:11'
            ],
            [
                'name.required' => 'name cannot be empty',
                'email.required' => 'email cannot be empty',
                'password.required' => 'password cannot be empty',
                'confirm_password.required' => 'confirm_password cannot be empty',
                'phone_number.required' => 'phone number cannot be empty',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $restoId = Restoran::where('user_id', Auth::id())->first();
        if (!$restoId) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found', 
                'data' => null
            ],404);
        }

        $password = $request->password;
        $password2 = $request->confirm_password;

        if ($password != $password2) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed registered driver', 
                'data' => null
            ],400);
        }
        
        // create user with roles driver
        $user = new User();
        $user->name = $request->name;
        $user->roles_id = 4;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt($request->password);


        if ($user->save()) {

            // mapping user to driver
            $driver = new Driver();
            $driver->user_id = $user->id;
            $driver->resto_id =  $restoId->id;
            $driver->is_available = true;
            if ($driver->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Success registered driver', 
                    'data' => $user
                ],200);
            }
        }

        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => 'Failed registered driver', 
            'data' => null
        ],400);
        
    }

    public function loginDriver(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|string|min:11',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'phone or password incorrect', 
                'data' => null
            ], 404);
        }
        $user = User::where('phone_number', $request->phone_number)->first();

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*60,
            'user' => auth()->user(),
        ],200);
    }

    public function getDriverByResto($restoId)
    {
        $dataDriver = array();
        $cekResto = Restoran::find($restoId);

        if (!$cekResto) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Restoran not found', 
                'data' => null,
            ],404);
        }

        $drivers = Driver::where('resto_id', $restoId)->get();
        if (!$drivers) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Driver not found', 
                'data' => null,
            ],404);
        }

        foreach ($drivers as $driver) 
        {
            $users = User::where('id', $driver->user_id)->first();
            $dataDriver[] = [
                'userData' => $users,
                'driverId' => $driver->id
            ];
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'success get all driver',
            'data' => $dataDriver
        ],200);
    }

    public function editRestoDriver(Request $request, $driverId)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|between:2,100',
            'phone_number' => 'required|string|min:11',
            'email' => 'string|email|max:100',
            'img' => 'mimes:jpeg,png,jpg,gif,svg|max:12048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $driver = Driver::find($driverId);

        if ($driver) {
            $user = User::find($driver->user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => 'User not found',
                    'data' => null,
                ],404);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;

            if($request->hasFile('img')) {
                if ($user->photo != null) {
                    $path = public_path('storage/').$user->photo;

                    if (file_exists($path)) {
                        try {
                            unlink($path);
                        } catch (Exception $e) {
                            return response()->json([
                                'success' => false,
                                'code' => 400,
                                'message' => $e->getMessage()
                            ],400);
                        }
                    }
                }

                $file = $request->file('img');
                $ekstension = $file->getClientOriginalExtension();
                $name = 'Driver_'.uniqid().'_'.Auth::user()->name.'.'.$ekstension;
                $request->img->move(public_path('storage'), $name);

                $user->photo = $name;
            }

            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Success to update driver data',
                    'data' => $user
                ],200);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Failed to update driver data',
                    'data' => null,
                ],400);
            }
        }else{
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Driver has not registered',
                'data' => null,
            ],404);
        }
    }

    public function driverProfile()
    {
        $profile = auth()->user();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed get user profile', 
                'data' => null,
            ],400);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get user profile', 
            'data' => $profile
        ],200);
    }

    public function updateDriverProfile(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|between:2,100',
            'phone_number' => 'required|string|min:11',
            'email' => 'string|email|max:100',
            'img' => 'mimes:jpeg,png,jpg,gif,svg|max:12048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $driver = auth()->user();
        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->phone_number = $request->phone_number;

        if($request->hasFile('img')) {
            if ($driver->photo != null) {
                $path = public_path('storage/').$driver->photo;

                if (file_exists($path)) {
                    try {
                        unlink($path);
                    } catch (Exception $e) {
                        return response()->json([
                            'success' => false,
                            'code' => 400,
                            'message' => $e->getMessage()
                        ],400);
                    }
                }
            }

            $file = $request->file('img');
            $ekstension = $file->getClientOriginalExtension();
            $name = 'Driver_'.uniqid().'_'.Auth::user()->name.'.'.$ekstension;
            $request->img->move(public_path('storage'), $name);

            $driver->photo = $name;
        }

        if ($driver->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success to update driver data',
                'data' => $driver
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed to update driver data',
                'data' => null,
            ],400);
        }
    }

    public function deleteDriver($driverId)
    {
        $driver = Driver::find($driverId);

        if (!$driver) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Driver not found',
                'data' => null,
            ],404);
        }

        $user = User::where(
            [
                'id' => $driver->user_id,
                'roles_id' => 4,
            ]
        )->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'User not found',
                'data' => null,
            ],404);
        }

        $cart = OrderCart::where('driver_id', $driverId)->first();
        
        if ($cart) {
            $cart->driver_id = null;
            if ($cart->save()) {
                if ($driver->delete() && $user->delete()) {
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => 'Success delete driver',
                        'data' => null,
                    ],200);
                }
            }
        }else{
            if ($driver->delete() && $user->delete()) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Success delete driver',
                    'data' => null,
                ],200);
            }
        }

        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => 'Failed delete driver data',
            'data' => null,
        ],400);

    }

    public function updateLocation(Request $request, $driverId)
    {
        $driver = Driver::where('id', $driverId)->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Driver not found',
                'data' => null,
            ],404);
        }

        $driver->lat = $request->lat;
        $driver->long = $request->long;

        if ($driver->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success update driver location',
                'data' => $driver,
            ],200);
        }
    }
}