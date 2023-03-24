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
            'content' => 'required | string',
            'thumnail' => 'required | string',
            'thumnail_alt_text' => 'required | string',
            'meta_keyword' => 'required | string',
            'meta_description' => 'required | string',
            'category' => 'required | string',
        ]);

        $blog =  DB::table('blog')->insert([
            'title' => $data['title'],
            'content' => $data['content'],
            'thumnail' => $data['thumnail'],
            'thumnail_alt_text' => $data['thumnail_alt_text'],
            'meta_keyword' => $data['meta_keyword'],
            'meta_description' => $data['meta_description'],
            'category' => $data['category'],
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
        if (!$blog) {
            return response([
                'message' => "Blog is empty.",
            ], 401);
        }
        $token = $request->bearerToken();

        $response = [
            'message' => "All blogs is presented",
            'blog' => $blog,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function readSingleBlog(Request $request)
    {
        $data = $request->validate([
            'blog_slug' => 'required | string',
        ]);
        $blog =  DB::table('blog')->where('slug', $data['blog_slug'])->first();
        if (!$blog) {
            return response([
                'message' => "Blog is empty.",
            ], 401);
        }
        $token = $request->bearerToken();

        $response = [
            'message' => "Single blog is presented",
            'blog' => $blog,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function updateBlog(Request $request)
    {
        $data = $request->validate([
            'blog_id' => 'required | string',
            'new_title' => 'nullable | string',
            'new_content' => 'nullable | string',
            'new_thumnail' => 'nullable | string',
            'new_thumnail_alt_text' => 'nullable | string',
            'new_meta_keyword' => 'nullable | string',
            'new_meta_description' => 'nullable | string',
            'new_category' => 'nullable | string',
        ]);


        $blog = DB::table('blog')->where('id', $data['blog_id'])->first();

        if (!array_key_exists('new_title', $data)) {
            $title = $blog->title;
        } else {
            $title = $data['new_title'];
        }
        if (!array_key_exists('new_content', $data)) {
            $content = $blog->content;
        } else {
            $content = $data['new_content'];
        }

        if (!array_key_exists('new_thumnail', $data)) {
            $thumnail = $blog->thumnail;
        } else {
            $thumnail = $data['new_thumnail'];
        }
        if (!array_key_exists('new_thumnail_alt_text', $data)) {
            $thumnail_alt_text = $blog->thumnail_alt_text;
        } else {
            $thumnail_alt_text = $data['new_thumnail_alt_text'];
        }
        if (!array_key_exists('new_meta_keyword', $data)) {
            $meta_keyword = $blog->meta_keyword;
        } else {
            $meta_keyword = $data['new_meta_keyword'];
        }
        if (!array_key_exists('new_meta_description', $data)) {
            $meta_description = $blog->meta_description;
        } else {
            $meta_description = $data['new_meta_description'];
        }
        if (!array_key_exists('new_category', $data)) {
            $category = $blog->category;
        } else {
            $category = $data['new_category'];
        }


        $blog = DB::table('blog')->where('id', $blog->id)->update([
            'title' => $title,
            'content' => $content,
            'thumnail' => $thumnail,
            'thumnail_alt_text' => $thumnail_alt_text,
            'meta_keyword' => $meta_keyword,
            'meta_description' => $meta_description,
            'category' => $category,
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
            'blog_id' => 'required | string',
        ]);

        DB::table('blog')->where('id', $data['blog_id'])->delete();

        $token = $request->bearerToken();

        $response = [
            'message' => "The blog is remove",
            'token' => $token,
        ];

        return response($response, 201);
    }
}
