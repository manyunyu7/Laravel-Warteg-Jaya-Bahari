<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpEmail;
use App\Models\User;
use App\Models\UserOTP;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'name' => 'required|string|between:2,100',
                'roles_id' => 'required|integer', Rule::in([1,2,3,4]),
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6',
                'phone_number' => 'required|string|min:11'
            ],
            [
                'name.required' => 'name cannot be empty',
                'email.required' => 'email cannot be empty',
                'roles_id.required' => 'roles cannot be empty',
                'password.required' => 'password cannot be empty',
                'confirm_password.required' => 'confirm_password cannot be empty',
                'phone_number.required' => 'phone number cannot be empty',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $password = $request->password;
        $password2 = $request->confirm_password;

        if ($password != $password2) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'password do not match', 
                'data' => null
            ]);
        }

        $data = User::create([
            'name' => $request->name,
            'roles_id' => $request->roles_id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password)
        ]);

        if (!$data) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed registered user', 
                'data' => null
            ]);
        }else{
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success registered user', 
                'data' => $data
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'email or password incorrect', 
                'data' => null
            ], 404);
        }
        $user = User::where('email',$request->email)->first();
        if ($user->email_verified_at == null) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Verified first', 
                'data' => null
            ],400);
        }

        return $this->createNewToken($token);

    }

    public function logout() 
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'user successfully logged out', 
        ],200);
    }

    public function refresh() 
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success get user profile', 
            'data' => auth()->user()
        ],200);
    }

    public function editProfile( Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'string|between:2,100',
            'email' => 'string|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = auth()->user();
            
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('img')) {
            $path = public_path('uploads/img/users/').$request->img;

            if (file_exists($path)) {
                try {
                    unlink($path);
                } catch (Exception $e) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => $e
                    ],400);
                }
            }

            $file = $request->file('img');
            $ekstension = $file->getClientOriginalExtension();
            $name = time().'_'.Auth::user()->name.'.'.$ekstension;
            $request->img->move(public_path('uploads/img/users'), $name);

            $user->photo = $name;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success edit profile', 
            'data' => $user
        ],200);
    }

    public function updateUserPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'new_password' => 'string|min:6',
            'confirm_password' => 'string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        if ($new_password !== $confirm_password) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'password do not match', 
                'data' => null
            ],400);
        }else{
            $user = auth()->user();
            $user->password = bcrypt($request->new_password);

            $user->save();

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success update password', 
                'data' => $user
            ],200);
        }
    }

    public function uploadProfilePicture(Request $request)
    {
        

        $validator = Validator::make($request->all(),
        [
            'img' => 'image:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'img.image' => 'image must be a valid image'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $userName = Auth::user()->name;

        $file = $request->file('img');
        $ekstension = $file->getClientOriginalExtension();
        $name = time() .'_'.$userName.'.'.$ekstension;
        $request->img->move(public_path('uploads/img/users'), $name);
        $user = auth()->user();
        $user->photo = $name;

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Success upload photo', 
                'data' => $user
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed upload photo', 
                'data' => $user
            ],400);
        }
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

    public function refreshToken()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function requestOTP(Request $request)
    {
        $user = User::where("email", $request->email)->first();

        if (!$user->id) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'User id not found', 
                'data' => null
            ],404);
        }

        do {
            $otp = rand(1000, 9999);
            $check = UserOTP::where('otp', $otp)->exists();
        } while ($check);

        $userOTP = UserOTP::updateOrCreate(
            [
                "user_id" => $user->id,
            ],
            [
                "otp" => $otp,
                "valid_until" => Carbon::now()->addMinutes(30),
            ]
        );
        
        if (!$userOTP) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => 'Failed create OTP', 
                'data' => null
            ],400);
        }

        Mail::to($request->email)->send(new OtpEmail($otp, $user->name));

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'OTP send succesfully', 
        ],200);
    }

    public function verifyOTP(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $checkOTP = UserOTP::where('user_id', $user->id)->where('otp',$request->otp)->first();

        if (!empty($checkOTP)) {
            if (Carbon::now()->lessThanOrEqualTo($checkOTP->valid_until)){
                User::where('email',$user->email)->update([
                    "email_verified_at" => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Success Verified account', 
                ],200);
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'OTP Code Expired', 
                ],401);
            }
        }else{
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'OTP Code Not Found', 
            ],404);
        }
        
    }
}
