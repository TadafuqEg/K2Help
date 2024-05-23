<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BaseGetController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommunityController;
use App\Http\Controllers\API\AccidentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/countries',[BaseGetController::class,'allCountries'])->name('all_countries');
Route::get('/about',[BaseGetController::class,'about'])->name('about');
Route::get('/FAQs',[BaseGetController::class,'FAQs'])->name('FAQs');
Route::post('/regist_volunteer',[AuthController::class,'regist_volunteer'])->name('regist_volunteer');
Route::post('/create_help',[AccidentController::class,'create_accident'])->name('create_help');

Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/posts',[CommunityController::class,'posts'])->name('posts');
Route::middleware('auth:sanctum')->group( function () {
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    

    Route::post('/create_post',[CommunityController::class,'create_post'])->name('create_post');
    Route::post('/create_comment',[CommunityController::class,'create_comment'])->name('create_comment');
    Route::post('/create_replay',[CommunityController::class,'create_replay'])->name('create_replay');


});
