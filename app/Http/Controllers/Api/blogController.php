<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Raw;

class blogController extends Controller
{
    public function postBlog(Request $request)
    {
        $data = $request->validate([
            'title' => 'required | string',
            'post' => 'required | string',
            // 'email' => 'required | string',
        ]);
        // $user = User::where('email', $data['email'])->first();
        $blog =  DB::table('blog')->insert([
            'title' => $data['title'],
            'post' => $data['post'],
        ]);

        $token = $request->bearerToken();

        $response = [
            'message' => "The blog is inserted",
            'blog' => $blog,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function readBlog(Request $request)
    {

        $blog =  DB::table('blog')->get();

        $token = $request->bearerToken();

        $response = [
            'message' => "All blogs is presented",
            'blog' => $blog,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function updateBlog(Request $request)
    {
        $data = $request->validate([
            'past_title' => 'required | string',
            'new_title' => 'nullable | string',
            'new_post' => 'required | string',
        ]);

        if (!array_key_exists('new_title', $data)) {
            $title = $data['past_title'];
        } else {
            $title = $data['new_title'];
        }
        $post = $data['new_post'];

        $blog = DB::table('blog')->where('title', $data['past_title'])->first();

        $blog = User::where('id', $blog->id)->update([
            'title' => $title,
            'post' => $post,
        ]);

        $token = $request->bearerToken();

        $response = [
            'message' => "The blog is updated",
            'blog' => $blog,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function deleteBlog(Request $request)
    {
        $data = $request->validate([
            'title' => 'required | string',
        ]);

        $blog = DB::table('blog')->where('title', $data['title'])->first();

        $blog  = User::where('id', $blog->id)->delete();

        $token = $request->bearerToken();

        $response = [
            'message' => "The blog is remove",
            'blog' => $blog,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
