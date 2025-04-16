<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionModificationController extends Controller
{

    public function index()
    {
        $questionReports = DB::table('question_reports')
            ->join('questions', 'question_reports.question_id', '=', 'questions.id')
            ->join('users', 'question_reports.user_email', '=', 'users.email')
            ->select(
                'question_reports.*',
                'questions.question as question_text',
                'users.email as user_email'
            )
            ->orderBy('question_reports.created_at', 'desc')
            ->paginate(10);

        return view('report_related.question-report-list', compact('questionReports'));
    }

    public function questionNoExplanation()
    {
        $questions = DB::table('questions')
            ->where(function ($query) {
                $query->where('explanation', '')
                    ->orWhere('explanation', 'N/A');
            })
            ->join('question_set', 'questions.exam_set_id', '=', 'question_set.id')
            ->select('questions.*', 'question_set.name as set_name')
            ->paginate(10);

        return view('questions-no-explanation', compact('questions'));
    }
}
