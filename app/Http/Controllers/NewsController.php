<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

use Validator;

class NewsController extends Controller
{
    public function list(Request $request)
    {
        $news = News::with('file')->get();
        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'لیست اخبار'
            ],
            'data' => $news
        ]);
    }

    public function add(Request $request)
    {
        $user = auth()->user();
        $validateNews = Validator::make($request->all(),
            [
                'title' => 'required',
                'content' => 'required',
                'imageId' => 'exists:files,id'
            ]);

        if($validateNews->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateNews->errors()
            ], 401);
        }

        $news = News::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'imageId' => $request->get('imageId'),
            'created_by' => $user->id,
        ]);

        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'خبر با موفقیت ثبت شد'
            ],
            'data' => $news
        ]);
    }


}
