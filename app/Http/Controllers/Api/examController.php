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
                'massage' => "Category does not exist.",
            ], 401);
        }
        $groups = DB::table('exam_groups')->join('exam_access', 'exam_groups.id', "=", 'exam_access.exam_group_id')
            ->where('exam_groups.exam_category_id', $cat->id)->where('exam_access.examinee', Auth::user()->id)->get();

        // $access = DB::table('exam_access')->where('examinee', Auth::user()->id)->get();

        // DB::table('users')
        //     ->join('contacts', 'users.id', '=', 'contacts.user_id')
        //     ->join('orders', 'users.id', '=', 'orders.user_id')
        //     ->select('users.*', 'contacts.phone', 'orders.price')
        //     ->get();

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
                'massage' => "Group does not exist.",
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
                'massage' => "Question Set does not exist.",
            ], 401);
        }
        $question = DB::table('questions')->where('exam_set_id', $questionSet->id)->get();
        $token = $request->bearerToken();
        $response = [
            'question' => $question,
            'token' => $token,
        ];

        return response($response, 201);
    }
}