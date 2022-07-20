<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required | string',
            'email' => 'required | unique:users',
            'password' => 'confirmed | required ',
            'role' => 'required | string',
            'mobile' => 'nullable | integer|size:15',
            'division' => 'nullable | string',
            'district' => 'nullable | string',
            'upazila' => 'nullable | string',
            'birth_date' => 'nullable | string',
            'university' => 'nullable | string',
            'gender' => 'nullable | string',

        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            // 'mobile'=> $data['mobile'],
            // 'division'=> $data['district'],
            // 'district'=> $data['upazila'],
            // 'upazila'=> $data['union'],
            // 'birth_date'=> $data['birth_date'],
            // 'university'=> $data['university'],
            // 'gender'=> $data['gender'],

        ]);

        $token = $user->createToken('adminControlToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {

        $data = $request->validate([
            'email' => 'required | unique:users',
            'password' => 'confirmed | required ',

        ]);

        $user = User::where('email',$data['email'])->get();

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response([
                'massage'=> "Email or password is incorrect"
            ],401);
        }

        $token = $user->createToken('adminControlToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);

    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Successfully logged out');
    }

    public function profileUpdate(){
        
    }
}
