<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class orderController extends Controller
{
    public function makeOrder(Request $request)
    {
        $data = $request->validate([
            'orderName' => 'required | string',
            'userEmail' => 'required | string',
            'amount' => 'required',
        ]);
        $user = User::where('email', $data['userEmail'])->first();

        $order =  DB::table('order')->insert([
            'name' => $data['orderName'],
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'status' => 'Processing',

        ]);

        $token = $request->bearerToken();

        $response = [
            'message' => "The order is made",
            'order' => $order,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
