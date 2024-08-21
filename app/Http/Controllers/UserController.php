<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function profile(Request $request){
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'firstName' => 'required',
                    'lastName' => 'required',
                    ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => [
                        'code' => 1,
                        'message' => 'Validation error'
                    ],
                    'data' => $validateUser->errors()->all()
                ], 401);
            }
            $user = \Auth::getUser();
            $user->first_name=$request->firstName;
            $user->last_name=$request->lastName;
            $user->save();

            return response()->json([
                'status' => [
                    'code' => 0,
                    'message' => 'با موفقیت وارد شدید'
                ],
                'data' => ['user'=>$user],


            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
