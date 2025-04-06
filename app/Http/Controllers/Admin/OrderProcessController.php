<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderProcessController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $orders = DB::table('order')
            ->join('users', 'order.user_id', '=', 'users.id')
            ->select('order.*', 'users.name as user_name')
            ->where('order.status', 'Pending')
            ->paginate(10);
        return view('order_related.new-order', ['orders' => $orders]);
    }

    public function process(Request $request)
    {
        $order = DB::table('order')->where('id', $request->id)->first();

        //here the 500 is based on an assume amount that will not conflict old and new changes
        if ($order->amount < 500) {
            $orderTime = "month";
        } else {
            $orderTime = "year";
        }

        if ($orderTime == "year") {
            DB::table('order_list')->insert([
                'name' => $order->name,
                'order_id' => $order->id,
                'deadline' => Carbon::now()->addYear(),
            ]);
        } else {
            DB::table('order_list')->insert([
                'name' => $order->name,
                'order_id' => $order->id,
                'deadline' => Carbon::now()->addMonth(),
            ]);
        }

        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 2,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 3,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 4,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 5,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 6,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 7,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 8,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 9,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 10,
        ]);
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 11,
        ]);
        if ($order->name == "সকল প্রিমিয়াম গ্রুপ + বিজ্ঞাপন মুক্ত") {
            DB::table('exam_access')->insert([
                'examinee' => $order->user_id,
                'exam_group_id' => 14,
            ]);
        }


        DB::table('order')->where('id', $order->id)->update([
            'status' => 'Approved'
        ]);


        return redirect()->route('order.index');
    }

    public function removeOrder(Request $request)
    {
        DB::table('order')->where('id', $request->id)->delete();
        return redirect()->route('order.index');
    }

    public function orderList()
    {
        $orderList = DB::table('order_list')
            ->join('order', 'order_list.order_id', '=', 'order.id')
            ->join('users', 'order.user_id', '=', 'users.id')
            ->select('order_list.*', 'users.name as user_name', 'users.email as user_email')
            ->paginate(10);

        return view('order_related.all-order', ['orderList' => $orderList]);
    }

    public function removeAccess(Request $request)
    {
        $orderList = DB::table('order_list')->where('id', $request->id)->first();
        $order = DB::table('order')->where('id', $orderList->order_id)->first();
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
        DB::table('order_list')->where('id', $request->id)->delete();
        DB::table('order')->where('id', $orderList->order_id)->delete();
        return redirect()->route('order.orderList');
    }
}
