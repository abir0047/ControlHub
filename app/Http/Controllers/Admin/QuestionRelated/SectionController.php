<?php

namespace App\Http\Controllers\Admin\QuestionRelated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $sectionData = DB::table('section')->paginate(10);
        return view('question_related.section', ['sectionData' => $sectionData]);
    }

    public function destroy($id)
    {
        DB::table('section')->where('id', $id)->delete();
        return back();
    }
    public function add()
    {
        return view('question_related.section-add');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        DB::table('section')->insert(['name' => $request->name]);
        return redirect()->route('section.index');
    }
    public function updateView($id)
    {
        $data = DB::table('section')->where('id', $id)->first();
        return view('question_related.section-update', ['data' => $data]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        DB::table('section')->where('id', $request->id)->update(['name' => $request->name]);
        return redirect()->route('section.index');
    }
}
