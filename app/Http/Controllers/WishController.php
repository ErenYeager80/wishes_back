<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Wish;
use Carbon\Carbon;
use Cassandra\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class WishController extends Controller
{

    function add(Request $request){
        $validator =  Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
            'imageId'=>'numeric|exists:files,id'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => [
                    'code' => 1,
                    'message' => 'Validation error'
                ],
                'data' => $validator->errors()->all()
            ], 401);
        }

        $user = \Auth::getUser();

            $wish=$user->wishes()->create([
                'content' => $request->get('content'),
                'title' => $request->get('title'),
                'image_id'=> $request->get('imageId')
            ]);

        $wish=Wish::with('file')->find($wish->id);
        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'wish successfully created'
            ],
            'data' => $wish
        ], 201);

    }

    function list(Request $request){
        $user = \Auth::getUser();
        $wishes = $user->wishes()->with('file')->get();
        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'wish successfully created'
            ],
            'data' => $wishes
        ], 200);
    }

    function done(Request $request,$id){
        $wish=Wish::find($id);
        if(!$wish){
            return response()->json([
                'status' => [
                    'code' => 1,
                    'message' => 'wish not exist'
                ],
            ], 404);
        }
        $user = \Auth::getUser();
        if($wish->user_id!=$user->id){
            return response()->json([
                'status' => [
                    'code' => 1,
                    'message' => 'you cannot change this wish'
                ],
            ], 401);
        }

        $wish->update(['done_at'=> Carbon::now()]);

        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'your wish is done'
            ],
            'data'=>$wish
        ], 200);
    }

}
