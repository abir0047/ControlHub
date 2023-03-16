<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class examController extends Controller
{
    public function getGroup(Request $request)
    {
        $data = $request->validate([
            'categoryName' => 'required | string',
        ]);
        $cat = DB::table('exam_categories')->where('name', $data['categoryName'])->first();
        if (!$cat) {
            return response([
                'message' => "Category does not exist.",
            ], 401);
        }
        $group1 = DB::table('exam_groups')->join('exam_access', 'exam_groups.id', "=", 'exam_access.exam_group_id')
            ->where('exam_groups.exam_category_id', $cat->id)->where('exam_access.examinee', Auth::user()->id)->pluck('name');

        $group2 = DB::table('exam_groups')->where('exam_groups.exam_category_id', $cat->id)->whereNotIn('name', $group1)->pluck('name');


        $groups = collect($group1)->map(function ($item) {
            return (object) ['name' => $item, 'access' => true];
        })->merge(collect($group2)->map(function ($item) {
            return (object) ['name' => $item, 'access' => false];
        }));

        $token = $request->bearerToken();
        $response = [
            'group' => $groups,
            'token' => $token,
        ];

        return response($response, 201);
    }
    public function getQuestionSet(Request $request)
    {
        $data = $request->validate([
            'groupName' => 'required | string',
        ]);
        $group = DB::table('exam_groups')->where('name', $data['groupName'])->first();
        if (!$group) {
            return response([
                'message' => "Group does not exist.",
            ], 401);
        }
        $examSet = DB::table('question_set')->where('exam_group_id', $group->id)->get();
        $token = $request->bearerToken();
        $response = [
            'examSet' => $examSet,
            'token' => $token,
        ];

        return response($response, 201);
    }
    public function getQuestion(Request $request)
    {
        $data = $request->validate([
            'questionSetName' => 'required | string',
        ]);
        $questionSet = DB::table('question_set')->where('name', $data['questionSetName'])->first();
        if (!$questionSet) {
            return response([
                'message' => "Question Set does not exist.",
            ], 401);
        }
        $question = DB::table('questions')->where('exam_set_id', $questionSet->id)->get();

        $question = $question->shuffle();

        foreach ($question as $q) {
            $options = [$q->option1, $q->option2, $q->option3, $q->option4];
            shuffle($options);
            $q->option1 = $options[0];
            $q->option2 = $options[1];
            $q->option3 = $options[2];
            $q->option4 = $options[3];
        }
        $token = $request->bearerToken();
        $response = [
            'question' => $question,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
