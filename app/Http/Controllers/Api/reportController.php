<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function inputReport(Request $request)
    {
        $data = $request->validate([
            'attempted' => 'required | string',
            'right' => 'required | string',
            'wrong' => 'required | string',
            'total_marks' => 'required | string',
            'taken_time' => 'required | string',
            'questionSetName' => 'required | string',
            'userEmail' => 'required | string',
        ]);
        $questionSet = DB::table('question_set')->where('name', $data['questionSetName'])->first();
        $user = User::where('email', $data['userEmail'])->first();

        $report =  DB::table('report')->insert([
            'attempted' => $data['attempted'],
            'right' => $data['right'],
            'wrong' => $data['wrong'],
            'total_marks' => $data['total_marks'],
            'taken_time' => $data['taken_time'],
            'exam_date' => date("Y-m-d", time()),
            'examinee' => $user->id,
            'question_set_id' => $questionSet->id,
        ]);


        $token = $request->bearerToken();

        $response = [
            'message' => "Report is inputed",
            'report' => $report,
            'token' => $token,
        ];

        return response($response, 201);
    }
    public function getReport(Request $request)
    {
        $data = $request->validate([
            'userEmail' => 'required | string',
        ]);
        $user = User::where('email', $data['userEmail'])->first();
        // $report = DB::table('report')->where('examinee', $user->id)->groupBy('question_set_id')->latest('exam_date')->get();
        $report = DB::table('report')->where('examinee', $user->id)->groupBy(['examinee', 'question_set_id'])->latest('exam_date')->get();

        $token = $request->bearerToken();

        $response = [
            'report' => $report,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
