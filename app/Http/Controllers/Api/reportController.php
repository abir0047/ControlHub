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

        $report = DB::table('question_set')
            ->where('examinee', $user->id)
            ->join('report', 'question_set.id', '=', 'report.question_set_id')
            ->where('question_set.exam_group_id', '!=', 1)
            ->join(DB::raw('(SELECT question_set_id, AVG(total_marks) AS average_total_marks FROM report GROUP BY question_set_id) AS report2'), 'question_set.id', '=', 'report2.question_set_id')
            ->select('report.*', 'question_set.*', 'report2.average_total_marks')
            ->get();

        $grouped_report = [];

        $set = 1;
        for ($group = 1; $group <= 10; $group++) {
            for ($i = 1; $i <= 10; $i++) {
                $grouped_report[] = [
                    'group_id' => $group + 1,
                    'group_name' => 'Group ' . strval($group),
                    'set_id' => $set + 5,
                    'set_name' => 'Set ' . strval($i),
                    'participate' => false,
                    'average_total_marks' => '',
                    'last_total_marks' => '',
                    'taken_time' => '',
                ];
                $set++;
            }
        }

        foreach ($report as $row) {
            foreach ($grouped_report as &$group) {
                if ($row->question_set_id == $group['set_id']) {
                    $group['participate'] = true;
                    $group['last_total_marks'] = $row->total_marks;
                    $group['average_total_marks'] = $row->average_total_marks;
                    $group['taken_time'] = $row->taken_time;
                }
            }
        }

        $token = $request->bearerToken();

        $response = [
            'report' => $grouped_report,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
