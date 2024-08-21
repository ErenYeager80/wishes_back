<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Omalizadeh\MultiPayment\Facades\PaymentGateway;
use Omalizadeh\MultiPayment\Invoice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'auth'],function (){
    Route::post("request-code", [AuthController::class, 'requestCode']);
    Route::post("login", [AuthController::class, 'login']);
    Route::get("me", [AuthController::class, 'getMe'])->middleware('jwt.verify');
});

Route::group(['prefix'=>'user','middleware'=>'jwt.verify'],function (){
    Route::post("profile", [UserController::class, 'profile']);
});

Route::group(['prefix'=>'wish','middleware'=>'jwt.verify'],function (){
    Route::post("", [WishController::class, 'add']);
    Route::get("", [WishController::class, 'list']);
    Route::put("/{id}/done", [WishController::class, 'done']);
});

Route::group(['prefix'=>'news'],function (){
    Route::post("", [NewsController::class, 'add'])->middleware(['jwt.verify']);
    Route::get("", [NewsController::class, 'list']);
});

Route::group(['prefix'=>'file','middleware'=>'jwt.verify'],function (){
    Route::post("", [FileController::class, 'upload']);
});
Route::get('invoice',function (){
    $invoice = new Invoice(10000);
    $invoice->setPhoneNumber("989123456789");

    return PaymentGateway::purchase($invoice, function (string $transactionId) {
        // Save transaction_id and do stuff...
    })->toJsonResponse();
});
