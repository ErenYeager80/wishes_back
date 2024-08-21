<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    function upload(Request $request){
        $user = \Auth::getUser();
        $validator =  Validator::make($request->all(),[
            'file'=>'required|image'
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

            $hash = sha1_file($request->file->path());
            $file = File::where(['hash' => $hash])->first();
            $imageName = time() . '.' . $request->file->extension();
            if (!$file) {
                $file = $user->files()->create([
                    'path' => 'images/' . $imageName,
                    'hash' => $hash
                ]);
                $request->file->move(public_path('images'), $imageName);
            }
        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'file added'
            ],
            'data' => $file
        ], 200);
    }
}
