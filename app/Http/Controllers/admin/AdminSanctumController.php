<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSanctumController extends Controller
{
    public function login(Request $req){
        try{
            $validate = $req->validate([
                'mobile'=>'required|min:10',
                'password'=>'required|min:3'
            ]);
            $admin = Admin::where('mobile', $validate['mobile'])->first();
            if($admin && Hash::check($validate['password'], $admin->password)){

                //delete the old token
                $admin->tokens()->delete();
                //create new one
                $token = $admin->createToken('AuthToken');
                return response()->json([
                    'status' => '1',
                    'message' => 'Login successful',
                    'user' => $admin,
                    'token' => $token
                ], 200); 
            }
            else{
                return response()->json([
                    'Status'=>'0',
                    'Message'=>"Unauthorized"
                ], 401);
            }

            // $user = auth()->guard('api')->user();
            
           
        }
        catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => 'Internal server error',
                'Error'=>$e->getMessage()
            ], 500);
        }
    }
}
