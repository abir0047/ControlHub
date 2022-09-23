<?php

namespace App\Http\Controllers\QuestionRelated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionSetController extends Controller
{
    public function index(){
        $sectionData = DB::table('question_set')->paginate(10);
        return view('question_related.question-set', ['sectionData' => $sectionData]);
    }
    public function search(Request $request)
    {
        $sectionData = DB::table('question_set')->where('exam_group_id',$request->exam_group_id)->paginate(10);
        return view('question_related.question-set', ['sectionData' => $sectionData]);
    }
    public function add(){
        $categories = DB::table('exam_categories')->get();
        return view('question_related.question-set-add',['categories'=>$categories]);
    }
    public function fetch(Request $request)
    {
        $value = $request->get('value');
        $category = DB::table('exam_categories')->where('name',$value)->first();
        $id = $category->id;
        $datas = DB::table('exam_groups')->where('exam_category_id', $id)->get();

        $output = '<option disabled selected value>Select one:</option>';
        foreach ($datas as $data) {
            $output .= '<option value="' . $data->name . '">
            ' . $data->name . '</option>';
        }
        echo $output;
    }
    public function store(Request $request){
        $this->validate($request,[
            'name'=> 'required',
            'exam_category'=>'required',
            'exam_group'=>'required'
        ]);
        $data = DB::table('exam_groups')->where('name', $request->exam_group)->first();
        DB::table('question_set')->insert(['name'=>$request->name, 'exam_group_id'=>$data->id]);
        return redirect()->route('question-set.index');
    }
    public function destroy(Request $request){
        DB::table('question_set')->where('id', $request->id)->delete();
        return back();
    }
    public function edit(Request $request){
        $data = DB::table('question_set')->where('id', $request->id)->first();
        $groups = DB::table('exam_groups')->where('exam_category_id', $request->Cwhere)->get();
        $categories = DB::table('exam_categories')->get();
        return view('question_related.question-set-update',['data'=>$data,'groups'=>$groups,'categories'=>$categories]);
    }
    public function update(Request $request){
        $this->validate($request,[
            'name'=> 'required',
            'exam_group'=>'required'
        ]);
        $data = DB::table('exam_groups')->where('name', $request->exam_group)->first();
        DB::table('question_set')->where('id', $request->id)->update(['name'=>$request->name, 'exam_group_id'=>$data->id]);
        return redirect()->route('question-set.index');
    }
}
