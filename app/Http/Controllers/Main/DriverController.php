<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Restoran;
use App\Models\User;
use Carbon\Carbon;
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

        // $data = User::create([
        //     'name' => $request->name,
        //     'roles_id' => 4,
        //     'email' => $request->email,
        //     'phone_number' => $request->phone_number,
        //     'email_verified_at' => Carbon::now(),
        //     'password' => bcrypt($request->password)
        // ]);
        
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

}
