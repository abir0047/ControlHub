<?php

namespace App\Http\Controllers\QuestionRelated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamGroupController extends Controller
{
    public function index(){
        $sectionData = DB::table('exam_groups')->paginate(10);
        return view('question_related.exam-group', ['sectionData' => $sectionData]);
    }
    public function search(Request $request)
    {
        $sectionData = DB::table('exam_groups')->where('exam_category_id',$request->exam_category_id)->paginate(10);
        return view('question_related.exam-group', ['sectionData' => $sectionData]);
    }
    public function add(){
        $datas = DB::table('exam_categories')->get();
        return view('question_related.exam-group-add',['datas'=>$datas]);
    }
    public function store(Request $request){
        $this->validate($request,[
            'name'=> 'required',
        ]);
        $data = DB::table('exam_categories')->where('name', $request->exam_category)->first();
        DB::table('exam_groups')->insert(['name'=>$request->name, 'exam_category_id'=>$data->id]);
        return redirect()->route('exam-group.index');
    }
    public function destroy(Request $request){
        DB::table('exam_groups')->where('id', $request->id)->delete();
        return back();
    }
    public function edit(Request $request){
        $data = DB::table('exam_groups')->where('id', $request->id)->first();
        $infos = DB::table('exam_categories')->get();
        return view('question_related.exam-group-update',['data'=>$data,'infos'=>$infos]);
    }
    public function update(Request $request){
        $this->validate($request,[
            'name'=> 'required',
        ]);
        $data = DB::table('exam_categories')->where('name', $request->exam_category)->first();
        DB::table('exam_groups')->where('id', $request->id)->update(['name'=>$request->name, 'exam_category_id'=>$data->id]);
        return redirect()->route('exam-group.index');
    }
}
