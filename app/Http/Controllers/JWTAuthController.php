<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JWTAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer_api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:5,20',
            'email' => 'required|email|unique:customers|max:50',
            'password' => 'required|string|min:6',
            'userrole' => 'required'    
        ]);

        
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()    
                ]);
            }else{
                $customer = Customer::create(array_merge($validator->validate(), ['password'=> hash::make($request->password)]));

            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully !',
                'customer' => $customer  
            ], 201);
            }
        
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',    
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()    
            ], 401);
        }

        if(!$token = auth()->guard('customer_api')->attempt($validator->validate())){
            return response()->json(['error' => 'Unauthorized user login attempt'], 401);
        }

        return $this->createNewToken($token);

    }

    public function profile(){
        return response()->json(auth()->guard('customer_api')->user(), 201);
    }

    public function logout(){
        auth()->guard('customer_api')->logout();
        return response()->json(['message', 'Successfully logged out !']);
    }

    public function refresh(){
        return $this->createNewToken(auth()->guard('customer_api')->refresh());
    }

    public function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('customer_api')->factory()->getTTL() * 60  
        ]);
    }



}
