<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

class OrderProcessController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $orders = DB::table('order')->where('status', 'Pending')->paginate(10);
        return view('order_related.new-order', ['orders' => $orders]);
    }
    public function process(Request $request)
    {
        $order = DB::table('order')->where('id', $request->id)->first();
        dd($order);
        return view('order_related.new-order-process', ['order' => $order]);
    }

    public function workLoad(Request $request)
    {
        $order = DB::table('order')->where('id', $request->id)->first();

        $orderList =  DB::table('order_list')->insert([
            'name' => $order->name,
            'order_id' => $order->id,
            'deadline' => $request->deadline,
        ]);

        $user = User::where('id', $order->user_id)->first();
        $examGroup = DB::table('exam_groups')->where('name', $order->name)->first();

        DB::table('exam_access')->insert([
            'examinee' => $user->id,
            'exam_group_id' => $examGroup->id,
        ]);

        DB::table('order')->where('id', $order->id)->update([
            'status' => 'Approved'
        ]);


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
        $examGroup = DB::table('exam_groups')->where('name', $orderList->name)->first();
        DB::table('exam_access')->where('exam_group_id', $examGroup->id)->delete();
        DB::table('order_list')->where('id', $request->id)->delete();
        DB::table('order')->where('id', $orderList->order_id)->delete();
        return redirect()->route('order.orderList');
    }
}
