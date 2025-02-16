<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
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
            'toBkash' => 'required',
            'fromBkash' => 'required',
            'transactionId' => 'required',
        ]);
        $user = User::where('email', $data['userEmail'])->first();

        $order =  DB::table('order')->insert([
            'name' => $data['orderName'],
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'toBkash' => $data['toBkash'],
            'fromBkash' => $data['fromBkash'],
            'transactionId' => $data['transactionId'],
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
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
                'message' => "Category does not exist.",
            ], 401);
        }
        $user = User::where('email', $data['userEmail'])->first();

        $access = DB::table('exam_access')->select('exam_groups.id', 'exam_groups.exam_category_id', 'exam_groups.name')
            ->join('exam_groups', 'exam_groups.id', 'exam_access.exam_group_id')
            ->where('exam_groups.exam_category_id', $cat->id)
            ->where('exam_access.examinee', $user->id)->get();

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

        $file = DB::table('order')
            ->select('exam_groups.id', 'exam_groups.exam_category_id', 'exam_groups.name')
            ->join('exam_groups', 'exam_groups.name', 'order.name')
            ->where('exam_groups.exam_category_id', $cat->id)
            ->where('order.user_id', $user->id)->get();

        foreach ($file as $f) {
            foreach ($groups as $key => $value) {
                if ($f == $value) {
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

    public function getMyGroups(Request $request)
    {
        $data = $request->validate([
            'categoryName' => 'required | string',
            'userEmail' => 'required | string',
        ]);
        $cat = DB::table('exam_categories')->where('name', $data['categoryName'])->first();
        if (!$cat) {
            return response([
                'message' => "Category does not exist.",
            ], 401);
        }
        $user = User::where('email', $data['userEmail'])->first();


        $file1 = DB::table('order')
            ->join('exam_access', 'order.user_id', '=', 'exam_access.examinee')
            ->join('exam_groups', 'exam_groups.id', '=', 'exam_access.exam_group_id')
            ->where('exam_groups.exam_category_id', $cat->id)
            ->where('order.status', 'Pending')
            ->where('order.user_id', $user->id)
            ->select('order.name', 'order.status')
            ->distinct()
            ->get();
        $file2 = DB::table('order')
            ->join('exam_access', 'order.user_id', '=', 'exam_access.examinee')
            ->join('exam_groups', 'exam_groups.id', '=', 'exam_access.exam_group_id')
            ->join('order_list', 'order.id', '=', 'order_list.order_id')
            ->where('exam_groups.exam_category_id', $cat->id)
            ->where('order.status', 'Approved')
            ->where('order.user_id', $user->id)
            ->select(['order.name', 'order_list.deadline'])
            ->distinct()
            ->get();

        $token = $request->bearerToken();

        $response = [
            'message' => "My groups are here.",
            'pendingOrder' => $file1,
            'approvedOrder' => $file2,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function couponPrice(Request $request)
    {
        $data = $request->validate([
            'couponName' => 'required | string',
        ]);

        $coupon = DB::table('coupon')->where('couponName', $data['couponName'])->first();

        if (!$coupon) {
            return response([
                'message' => "Your coupon is invalid.",
            ], 401);
        }
        $response = [
            'message' => "Your coupon is here here.",
            'price' => $coupon->couponPrice,
        ];

        return response($response, 201);
    }

    public function checkAccess(Request $request)
    {
        $orderList = DB::table('order_list')->get();

        foreach ($orderList as $singleOrder) {
            if (Carbon::today()->gt(Carbon::parse($singleOrder->deadline))) {
                $order = DB::table('order')->where('id', $singleOrder->order_id)->first();

                DB::table('exam_access')->where('exam_group_id', 2)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 3)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 4)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 5)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 6)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 7)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 8)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 9)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 10)->where("examinee", $order->user_id)->delete();
                DB::table('exam_access')->where('exam_group_id', 11)->where("examinee", $order->user_id)->delete();
                if ($order->name == "সকল প্রিমিয়াম গ্রুপ + বিজ্ঞাপন মুক্ত") {
                    DB::table('exam_access')->where('exam_group_id', 14)->where("examinee", $order->user_id)->delete();
                }
                DB::table('order_list')->where('id', $singleOrder->id)->delete();
                DB::table('order')->where('id', $singleOrder->order_id)->delete();
            }
        }

        $response = [
            'message' => "Access is checked",
        ];

        return response($response, 201);
    }

    public function adFree(Request $request)
    {
        $data = $request->validate([
            'userEmail' => 'required | string',
        ]);
        $user = User::where('email', $data['userEmail'])->first();
        $info = DB::table('exam_access')->where('exam_group_id', 14)->where("examinee", $user->id)->First();

        if ($info == null) {
            $adFree = false;
        } else {
            $adFree = true;
        }

        $response = [
            'message' => "Access is checked",
            'adFree' => $adFree,
        ];

        return response($response, 201);
    }

    public function barFullAccess(Request $request)
    {
        $data = $request->validate([
            'userEmail' => 'required | string',
        ]);
        $user = User::where('email', $data['userEmail'])->first();
        $info = DB::table('exam_access')->where('exam_group_id', 9)->where("examinee", $user->id)->First();

        if ($info == null) {
            $barFullAccess = false;
        } else {
            $barFullAccess = true;
        }

        $response = [
            'message' => "Access is checked",
            'barFullAccess' => $barFullAccess,
        ];

        return response($response, 201);
    }
}
