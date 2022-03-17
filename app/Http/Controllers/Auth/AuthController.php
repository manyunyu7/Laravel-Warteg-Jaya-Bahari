<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'name' => 'required|string|between:2,100',
                'roles_id' => 'required|integer', Rule::in([1,2,3,4]),
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6',
            ],
            [
                'name.required' => 'name cannot be empty',
                'email.required' => 'email cannot be empty',
                'roles_id.required' => 'roles cannot be empty',
                'password.required' => 'password cannot be empty',
                'confirm_password.required' => 'confirm_password cannot be empty',
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
            ]);
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
        ]);
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
        ]);
    }

    public function editProfile( Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'string|between:2,100',
            'email' => 'string|email|max:100|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = auth()->user();
            
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Success edit profile', 
            'data' => $user
        ]);

    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*60,
            'user' => auth()->user(),
        ]);
    }
}
