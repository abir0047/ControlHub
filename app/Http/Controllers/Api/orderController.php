<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Groups;

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
        if (!$cat) {
            return response([
                'massage' => "Category does not exist.",
            ], 401);
        }
        $access = DB::table('exam_access')->select('exam_groups.id', 'exam_groups.exam_category_id', 'exam_groups.name')
            ->join('users', 'users.id', '=', 'exam_access.examinee')
            ->join('exam_groups', 'exam_groups.id', 'exam_access.exam_group_id')
            ->where('exam_groups.exam_category_id', $cat->id)
            ->where('users.email', $data['userEmail'])->get();

        $groups = DB::table('exam_groups AS g1')
            ->where('g1.exam_category_id', $cat->id)
            ->get();

        foreach ($access as $a) {
            foreach ($groups as $key => $value) {
                if ($a == $value) {
                    $groups->forget($key);
                }
            }
        }

        $token = $request->bearerToken();
        $response = [
            'groups' => $groups,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
