<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class reportController extends Controller
{
    private function getBanglaNumber($englishNumber)
    {
        $banglaDigits = [
            '০',
            '১',
            '২',
            '৩',
            '৪',
            '৫',
            '৬',
            '৭',
            '৮',
            '৯'
        ];

        $banglaNumber = '';

        for ($i = 0; $i < strlen($englishNumber); $i++) {
            $digit = intval($englishNumber[$i]);
            $banglaNumber .= $banglaDigits[$digit];
        }

        return $banglaNumber;
    }


    public function inputReport(Request $request)
    {
        // Main report validation
        $mainData = $request->validate([
            'userEmail' => 'required|email|exists:users,email',
            'questionSetName' => 'required|string|exists:question_set,name',
            'total_questions' => 'required|integer|min:1',
            'attempted' => 'required|integer|min:0',
            'correct' => 'required|integer|min:0',
            'wrong' => 'required|integer|min:0',
            'total_marks' => 'required|numeric',
            'time_taken' => 'required|integer|min:1', // in seconds
            'sections' => 'required|array|min:1'
        ]);

        // Section data validation
        $sectionRules = [
            'sections.*.section_name' => 'required|string|max:255',
            'sections.*.total_questions' => 'required|integer|min:1',
            'sections.*.attempted' => 'required|integer|min:0',
            'sections.*.correct' => 'required|integer|min:0',
            'sections.*.wrong' => 'required|integer|min:0',
            'sections.*.total_marks' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $sectionRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get related IDs
        $questionSet = DB::table('question_set')->where('name', $mainData['questionSetName'])->first();
        $user = User::where('email', $mainData['userEmail'])->first();

        try {
            // Use database transaction
            return DB::transaction(function () use ($mainData, $questionSet, $user) {
                // Create main report
                $reportId = DB::table('report')->insertGetId([
                    'user_id' => $user->id,
                    'question_set_id' => $questionSet->id,
                    'total_questions' => $mainData['total_questions'],
                    'attempted' => $mainData['attempted'],
                    'correct' => $mainData['correct'],
                    'wrong' => $mainData['wrong'],
                    'total_marks' => $mainData['total_marks'],
                    'time_taken' => $mainData['time_taken'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert section analyses
                $sections = [];
                foreach ($mainData['sections'] as $section) {
                    $sections[] = [
                        'report_id' => $reportId,
                        'section_name' => $section['section_name'],
                        'total_questions' => $section['total_questions'],
                        'attempted' => $section['attempted'],
                        'correct' => $section['correct'],
                        'wrong' => $section['wrong'],
                        'total_marks' => $section['total_marks'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('section_based_analysis')->insert($sections);

                return response()->json([
                    'message' => 'Report and sections saved successfully',
                    'report_id' => $reportId
                ], 201);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to save report',
                'error' => $e->getMessage()
            ], 500);
        }
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
                    'group_name' => 'গ্রুপ ' . $this->getBanglaNumber(strval($group)),
                    'set_id' => $set + 5,
                    'set_name' => 'সেট ' . $this->getBanglaNumber(strval($i)),
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
        $report = $grouped_report;

        $token = $request->bearerToken();

        $response = [
            'report' => $report,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
