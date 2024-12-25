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

        // if ($order->name = "বার কাউন্সিল - মাসিক") {
        //     $orderType = "barCouncil";
        //     $orderTime = "month";
        // } elseif ($order->name = "বার কাউন্সিল - বার্ষিক") {
        //     $orderType = "barCouncil";
        //     $orderTime = "year";
        // } elseif ($order->name = "জুডিশিয়ারি - মাসিক") {
        //     $orderType = "judiciary";
        //     $orderTime = "month";
        // } elseif ($order->name = "জুডিশিয়ারি - বার্ষিক") {
        //     $orderType = "judiciary";
        //     $orderTime = "year";
        // }

        if ($order->name = "বিজ্ঞাপন মুক্ত - মাসিক") {
            $orderTime = "month";
        } elseif ($order->name = "বিজ্ঞাপন মুক্ত - বার্ষিক") {
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
        // if ($orderType == "barCouncil") {
        //     $examGroups = DB::table('exam_groups')->where('exam_category_id', 1)->whereNot('name', 'বিগত বছরের প্রশ্নসমূহ')->get();
        // } elseif ($orderType == "judiciary") {
        //     $examGroups = DB::table('exam_groups')->where('exam_category_id', 2)->whereNot('name', 'বিগত বছরের প্রশ্নসমূহ')->get();
        // }
        // foreach ($examGroups as $examGroup) {
        //     DB::table('exam_access')->insert([
        //         'examinee' => $order->user_id,
        //         'exam_group_id' => $examGroup->id,
        //     ]);
        // }

        // Here 9 is just a picked number
        DB::table('exam_access')->insert([
            'examinee' => $order->user_id,
            'exam_group_id' => 9,
        ]);


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
        $orderList = DB::table('order_list')->paginate(10);
        return view('order_related.all-order', ['orderList' => $orderList]);
    }

    public function removeAccess(Request $request)
    {
        $orderList = DB::table('order_list')->where('id', $request->id)->first();
        $order = DB::table('order')->where('id', $orderList->order_id)->first();
        // if ($orderList->name = "বার কাউন্সিল - মাসিক") {
        //     $orderType = "barCouncil";
        //     $orderTime = "month";
        // } elseif ($orderList->name = "বার কাউন্সিল - বার্ষিক") {
        //     $orderType = "barCouncil";
        //     $orderTime = "year";
        // } elseif ($orderList->name = "জুডিশিয়ারি - মাসিক") {
        //     $orderType = "judiciary";
        //     $orderTime = "month";
        // } elseif ($orderList->name = "জুডিশিয়ারি - বার্ষিক") {
        //     $orderType = "judiciary";
        //     $orderTime = "year";
        // }
        // if ($orderType == "barCouncil") {
        //     $examGroups = DB::table('exam_groups')->where('exam_category_id', 1)->whereNot('name', 'বিগত বছরের প্রশ্নসমূহ - বার কাউন্সিল')->get();
        // } elseif ($orderType == "judiciary") {
        //     $examGroups = DB::table('exam_groups')->where('exam_category_id', 2)->whereNot('name', 'বিগত বছরের প্রশ্নসমূহ - জুডিশিয়ারি')->get();
        // }
        // foreach ($examGroups as $examGroup) {
        //     DB::table('exam_access')->where('exam_group_id', $examGroup->id)->where("examinee", $order->user_id)->delete();
        // }
        DB::table('exam_access')->where('exam_group_id', 9)->where("examinee", $order->user_id)->delete();
        DB::table('order_list')->where('id', $request->id)->delete();
        DB::table('order')->where('id', $orderList->order_id)->delete();
        return redirect()->route('order.orderList');
    }
}
