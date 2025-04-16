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
            'time_taken' => 'required|integer|min:1',
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
            'userEmail' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $data['userEmail'])->first();

        // Get all reports with section analysis and question set details
        $reports = DB::table('report')
            ->where('user_id', $user->id)
            ->join('question_set', 'report.question_set_id', '=', 'question_set.id')
            ->leftJoin('section_based_analysis', 'report.id', '=', 'section_based_analysis.report_id')
            ->select(
                'report.*',
                'question_set.name as question_set_name',
                'section_based_analysis.section_name',
                'section_based_analysis.total_questions as section_total',
                'section_based_analysis.attempted as section_attempted',
                'section_based_analysis.correct as section_correct',
                'section_based_analysis.wrong as section_wrong',
                'section_based_analysis.total_marks as section_marks'
            )
            ->orderBy('report.created_at', 'desc')
            ->get();

        // Group reports and their sections
        $groupedReports = $reports->groupBy('id')->map(function ($reportGroup) {
            $mainReport = $reportGroup->first();
            return [
                'id' => $mainReport->id,
                'question_set' => $mainReport->question_set_name,
                'date' => $mainReport->created_at,
                'total_questions' => $mainReport->total_questions,
                'attempted' => $mainReport->attempted,
                'correct' => $mainReport->correct,
                'wrong' => $mainReport->wrong,
                'total_marks' => $mainReport->total_marks,
                'time_taken' => $mainReport->time_taken,
                'sections' => $reportGroup->map(function ($section) {
                    return [
                        'name' => $section->section_name,
                        'total' => $section->section_total,
                        'attempted' => $section->section_attempted,
                        'correct' => $section->section_correct,
                        'wrong' => $section->section_wrong,
                        'marks' => $section->section_marks
                    ];
                })->filter()->values()
            ];
        })->values();

        // Calculate summary statistics
        $totalExams = $groupedReports->count();
        $averageScore = $groupedReports->avg('total_marks');
        $totalTime = $groupedReports->sum('time_taken');

        // Prepare performance timeline data
        $timelineData = $groupedReports->map(function ($report, $index) {
            return [
                'x' => $index + 1,
                'y' => $report['total_marks'],
                'date' => date('d M Y', strtotime($report['date']))
            ];
        });

        // Prepare subject performance data
        $subjectPerformance = $groupedReports->flatMap(function ($report) {
            return collect($report['sections'])->map(function ($section) use ($report) {
                return [
                    'subject' => $section['name'],
                    'score' => $section['marks'],
                    'date' => $report['date']
                ];
            });
        })->groupBy('subject')->map(function ($sections, $subject) {
            return [
                'subject' => $subject,
                'average_score' => $sections->avg('score'),
                'total_attempts' => $sections->count()
            ];
        })->values();

        // Prepare weakness analysis
        $weaknessAnalysis = $subjectPerformance->sortBy('average_score')
            ->take(3)
            ->map(function ($subject) {
                return [
                    'subject' => $subject['subject'],
                    'score' => round($subject['average_score'], 2)
                ];
            });

        // Format final response
        $response = [
            'headerSummary' => [
                'totalExams' => $this->getBanglaNumber((string)$totalExams),
                'averageScore' => $this->getBanglaNumber(number_format($averageScore, 2)),
                'totalTime' => $this->getBanglaNumber(gmdate('H:i', $totalTime))
            ],
            'performanceTimeline' => $timelineData,
            'subjectPerformance' => $subjectPerformance,
            'weaknessAnalysis' => $weaknessAnalysis,
            'detailedReports' => $groupedReports
        ];

        return response()->json([
            'success' => true,
            'data' => $response,
            'token' => $request->bearerToken()
        ]);
    }

    public function submitQuestionReport(Request $request)
    {
        $validated = $request->validate([
            'userEmail' => 'required|email|exists:users,email',
            'questionId' => 'required|exists:questions,id',
            'reasons.question_wrong' => 'boolean',
            'reasons.answer_wrong' => 'boolean',
            'reasons.explanation_wrong' => 'boolean',
            'reasons.typo_mistake' => 'boolean',
            'reasons.others' => 'boolean',
            'others_text' => 'nullable|string|max:255'
        ]);

        $userEmail = $validated['userEmail'];
        $questionId = $validated['questionId'];
        $reasons = $validated['reasons'];

        $data = [
            'question_wrong' => $reasons['question_wrong'] ?? false,
            'answer_wrong' => $reasons['answer_wrong'] ?? false,
            'explanation_wrong' => $reasons['explanation_wrong'] ?? false,
            'typo_mistake' => $reasons['typo_mistake'] ?? false,
            'others' => $reasons['others'] ?? false,
            'others_text' => $validated['others_text'] ?? null,
        ];

        // Check if a report already exists
        $existingReport = DB::table('question_reports')
            ->where('user_email', $userEmail)
            ->where('question_id', $questionId)
            ->exists();

        if ($existingReport) {
            // Update existing report and set updated_at
            $data['updated_at'] = now();
            DB::table('question_reports')
                ->where('user_email', $userEmail)
                ->where('question_id', $questionId)
                ->update($data);
        } else {
            // Insert new report with timestamps
            $data['user_email'] = $userEmail;
            $data['question_id'] = $questionId;
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('question_reports')->insert($data);
        }

        // Fetch the latest report data
        $report = DB::table('question_reports')
            ->where('user_email', $userEmail)
            ->where('question_id', $questionId)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully',
            'data' => $report
        ], 201);
    }
}
