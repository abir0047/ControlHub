<?php

namespace App\Http\Controllers\QuestionRelated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamCategoriesController extends Controller
{

    public function index(){
        $sectionData = DB::table('exam_categories')->paginate(10);
        return view('question_related.exam-categories', ['sectionData' => $sectionData]);
    }
    public function add(){
        return view('question_related.exam-categories-add');
    }
    public function store(Request $request){
        $this->validate($request,[
            'name'=> 'required',
            'description' => 'required',
        ]);
        DB::table('exam_categories')->insert(['name'=>$request->name, 'description'=>$request->description]);
        return redirect()->route('exam-categories.index');
    }
    public function destroy(Request $request){
        DB::table('exam_categories')->where('id', $request->id)->delete();
        return back();
    }
    public function updateView(Request $request){
        $data = DB::table('exam_categories')->where('id', $request->id)->first();
        return view('question_related.exam-categories-update',['data'=>$data]);
    }

    public function update(Request $request){
        $this->validate($request,[
            'name'=> 'required',
            'description' => 'required',
        ]);
        DB::table('exam_categories')->where('id', $request->id)->update(['name'=>$request->name, 'description'=>$request->description]);
        return redirect()->route('exam-categories.index');
    }
}
