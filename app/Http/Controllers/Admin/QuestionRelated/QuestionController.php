<?php

namespace App\Http\Controllers\Admin\QuestionRelated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $sectionData = DB::table('questions')->paginate(10);
        return view('question_related.question', ['sectionData' => $sectionData]);
    }
    public function search(Request $request)
    {
        $sectionData = DB::table('questions')->where('exam_set_id', $request->exam_set_id)->paginate(10);
        return view('question_related.question', ['sectionData' => $sectionData]);
    }
    public function add()
    {
        $categories = DB::table('exam_categories')->get();
        $sections = DB::table('section')->get();
        return view(
            'question_related.question-add',
            [
                'categories' => $categories,
                'sections' => $sections,
            ]
        );
    }
    public function fetch(Request $request)
    {
        $value = $request->get('value');
        $dependent = $request->get('dependent');
        if ($dependent == 'exam_group') {
            $category = DB::table('exam_categories')->where('name', $value)->first();
            $id = $category->id;
            $datas = DB::table('exam_groups')->where('exam_category_id', $id)->get();
        } else {
            $group = DB::table('exam_groups')->where('name', $value)->first();
            $id = $group->id;
            $datas = DB::table('question_set')->where('exam_group_id', $id)->get();
        }
        $output = '<option disabled selected value>Select one:</option>';
        foreach ($datas as $data) {
            $output .= '<option value="' . $data->name . '">
            ' . $data->name . '</option>';
        }
        echo $output;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'exam_category' => 'required',
            'exam_group' => 'required',
            'question_set' => 'required',
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'explanation' => 'required',
            'section' => 'required',
        ]);
        if ($request->correct_answer == 'option1') $correct = $request->option1;
        else if ($request->correct_answer == 'option2') $correct = $request->option2;
        else if ($request->correct_answer == 'option3') $correct = $request->option3;
        else  $correct = $request->option4;
        $data = DB::table('question_set')->where('name', $request->question_set)->first();
        DB::table('questions')->insert([
            'question' => $request->question,
            'exam_set_id' => $data->id,
            'option1' => $request->option1,
            'option2' => $request->option2,
            'option3' => $request->option3,
            'option4' => $request->option4,
            'correct_answer' => $correct,
            'explanation' => $request->explanation,
            'section' => $request->section,
        ]);
        return redirect()->route('question.index');
    }
    public function destroy(Request $request)
    {
        DB::table('questions')->where('id', $request->id)->delete();
        return back();
    }
    public function edit(Request $request)
    {
        $question = DB::table('questions')->where('id', $request->id)->first();
        $question_set = DB::table('question_set')->where('id', $question->exam_set_id)->first();
        $exam_group = DB::table('exam_groups')->where('id', $question_set->exam_group_id)->first();
        $exam_category = DB::table('exam_categories')->where('id', $exam_group->exam_category_id)->first();
        $categories = DB::table('exam_categories')->get();
        $groups = DB::table('exam_groups')->where('exam_category_id', $exam_category->id)->get();
        $examSets = DB::table('question_set')->where('exam_group_id', $exam_group->id)->get();
        $sections = DB::table('section')->get();
        return view('question_related.question-update', [
            'question' => $question,
            'question_set' => $question_set,
            'exam_group' => $exam_group,
            'exam_category' => $exam_category,
            'categories' => $categories,
            'groups' => $groups,
            'examSets' => $examSets,
            'sections' => $sections,
        ]);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'exam_category' => 'required',
            'exam_group' => 'required',
            'question_set' => 'required',
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'explanation' => 'required',
            'section' => 'required',
        ]);
        if ($request->correct_answer == 'option1') $correct = $request->option1;
        else if ($request->correct_answer == 'option2') $correct = $request->option2;
        else if ($request->correct_answer == 'option3') $correct = $request->option3;
        else  $correct = $request->option4;
        $data = DB::table('question_set')->where('name', $request->question_set)->first();
        DB::table('questions')->where('id', $request->id)->update([
            'question' => $request->question,
            'exam_set_id' => $data->id,
            'option1' => $request->option1,
            'option2' => $request->option2,
            'option3' => $request->option3,
            'option4' => $request->option4,
            'correct_answer' => $correct,
            'explanation' => $request->explanation,
            'section' => $request->section,
        ]);
        return redirect()->route('question.index');
    }
}
