<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Melipayamak\MelipayamakApi;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function getMe(){
        $user = auth()->user();
        return response()->json([
            'status' => [
                'code' => 0,
                'message' => 'اطلاعات کاربر'
            ],
            'data' => $user
        ]);
    }
    public function register(Request $request): JsonResponse
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required|unique:users,phone',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            $token = JWTAuth::fromUser($user);

            $user = $user->toArray();
            $user['token'] = $token;
            return response()->json([
                'status' => [
                    'code' => 0,
                    'message' => 'با موفقیت وارد شدید'
                ],
                'data' => $user,


            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function requestCode(Request $request){
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'username' => 'required',
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
            $user = User::firstOrCreate(['phone'=>$request->username]);

            $code= mt_rand(100000, 999999);
            $expiresAt=Carbon::now()->addMinutes(2);
            try{
                $username = '09153291368';
                $password = '6L05H';
                $api = new MelipayamakApi($username,$password);
                $sms = $api->sms();
                $to = $user->phone;
                $from = '50002710091368';
                $text = $code;
                $response = $sms->send($to,$from,$text);
                $json = json_decode($response);

                $user=$user->update(['otp_code'=>Hash::make($code),'otp_expires_at'=>$expiresAt]);

                return response()->json([
                    'status' => [
                        'code' => 0,
                        'message' => 'با موفقیت وارد شدید'
                    ],
                    'data' => ['user'=>$user,'expires_at'=>$expiresAt,'code'=>$code,'json'=>$json],


                ]);
            }catch(\Exception $e){

            }


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'username' => 'required',
                    'code' => 'required',
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
            $user = User::where(['phone'=>$request->username])->first();

            if($user->otp_expires_at<Carbon::now()){
                return response()->json([
                    'status' => [
                        'code' => 2,
                        'message' => 'کد منقضی شده است'
                    ],
                    'data' => null
                ], 401);
            }

            if(!Hash::check($request->code ,$user->otp_code)){
                return response()->json([
                    'status' => [
                        'code' => 3,
                        'message' => 'کد وارد شده غلط است.'
                    ],
                    'data' => null
                ], 401);
            }

            $token=JWTAuth::fromUser($user);
            $user['token'] = $token;
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
