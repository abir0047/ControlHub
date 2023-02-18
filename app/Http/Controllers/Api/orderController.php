<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function getOrderOptions(Request $request)
    {
        $data = $request->validate([
            'categoryName' => 'required | string',
            'userEmail' => 'required | string',
        ]);
        $cat = DB::table('exam_categories')->where('name', $data['categoryName'])->first();
        $groups = User::where('email', $data['userEmail'])->join('exam_access', 'users.id', "=", 'exam_access.examinee')
            ->join('exam_groups', 'exam_groups.id', '=', 'exam_access.exam_group_id')->where('exam_groups.exam_category_id', $cat->id)
            // ->whereNull('exam_access.exam_group_id')
            ->get();
        if (!$cat) {
            return response([
                'massage' => "Category does not exist.",
            ], 401);
        }

        // $groups = DB::table('exam_groups')->leftJoin('exam_access', 'exam_groups.id', "=", 'exam_access.exam_group_id')
        //     ->where('exam_groups.exam_category_id', $cat->id)->whereNull('exam_access.exam_group_id')->get();

        $token = $request->bearerToken();
        $response = [
            'groups' => $groups,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
