<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\website\AuthController;
use App\Http\Controllers\website\UserController;
use App\Http\Controllers\website\LocationController;
use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\website\CommunityController;
use App\Http\Controllers\website\SettingController;
/*
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    
    if(!auth()->user()){
        return redirect('/login');
    }else{
        return redirect('/home');
    }
});
Route::get('/login', [AuthController::class, 'login_view'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::group(['middleware' => ['admin']], function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    /////////////////////////////////////////
    Route::any('/users', [UserController::class, 'index'])->name('users'); 
    Route::get('/users/create', [UserController::class, 'create'])->name('add.user');
    Route::post('/users/create', [UserController::class, 'store'])->name('create.user');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('edit.user');
    Route::post('/user/update/{id}', [UserController::class, 'update'])->name('update.user');
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('delete.user');
    //////////////////////////////////////////
    Route::any('/states', [LocationController::class, 'index_states'])->name('states'); 
    Route::get('/states/create', [LocationController::class, 'create_state'])->name('add.state');
    Route::post('/states/create', [LocationController::class, 'store_state'])->name('create.state');
    Route::get('/state/edit/{id}', [LocationController::class, 'edit_state'])->name('edit.state');
    Route::post('/state/update/{id}', [LocationController::class, 'update_state'])->name('update.state');
    Route::get('/state/delete/{id}', [LocationController::class, 'delete_state'])->name('delete.state');
    //////////////////////////////////////////
    Route::any('/dependencies', [LocationController::class, 'index_dependencies'])->name('dependencies'); 
    Route::get('/dependencies/create', [LocationController::class, 'create_dependency'])->name('add.dependency');
    Route::post('/dependencies/create', [LocationController::class, 'store_dependency'])->name('create.dependency');
    Route::get('/dependency/edit/{id}', [LocationController::class, 'edit_dependency'])->name('edit.dependency');
    Route::post('/dependency/update/{id}', [LocationController::class, 'update_dependency'])->name('update.dependency');
    Route::get('/dependency/delete/{id}', [LocationController::class, 'delete_dependency'])->name('delete.dependency');
    //////////////////////////////////////////
    Route::any('/streets', [LocationController::class, 'index_streets'])->name('streets'); 
    Route::get('/streets/create', [LocationController::class, 'create_street'])->name('add.street');
    Route::post('/streets/create', [LocationController::class, 'store_street'])->name('create.street');
    Route::get('/street/edit/{id}', [LocationController::class, 'edit_street'])->name('edit.street');
    Route::post('/street/update/{id}', [LocationController::class, 'update_street'])->name('update.street');
    Route::get('/street/delete/{id}', [LocationController::class, 'delete_street'])->name('delete.street');
    Route::get('/cities_of_country',[LocationController::class, 'cities_of_country'])->name('get.dependencies');
    //////////////////////////////////////////
    Route::any('/help_requests', [HomeController::class, 'help_requests'])->name('help_requests'); 
    Route::get('/help_request/show/{id}', [HomeController::class, 'help_request'])->name('help_request');
     //////////////////////////////////////////
     Route::any('/volunteers', [HomeController::class, 'volunteers'])->name('volunteers'); 
     Route::get('/volunteer/show/{id}', [HomeController::class, 'volunteer'])->name('volunteer');
     //////////////////////////////////////////
    Route::any('/FAQs', [HomeController::class, 'FAQs'])->name('FAQs'); 
    Route::get('/FAQs/create', [HomeController::class, 'create_FAQ'])->name('add.FAQ');
    Route::post('/FAQs/create', [HomeController::class, 'store_FAQ'])->name('create.FAQ');
    Route::get('/FAQ/edit/{id}', [HomeController::class, 'edit_FAQ'])->name('edit.FAQ');
    Route::post('/FAQ/update/{id}', [HomeController::class, 'update_FAQ'])->name('update.FAQ');
    Route::get('/FAQ/delete/{id}', [HomeController::class, 'delete_FAQ'])->name('delete.FAQ');
    //////////////////////////////////////////////
    Route::get('/community',[CommunityController::class,'index'])->name('community');
    Route::post('/create_post', [CommunityController::class, 'create_post'])->name('create_post');
    Route::get('/delete_post', [CommunityController::class, 'delete_post'])->name('delete_post');
    Route::post('/update_post', [CommunityController::class, 'update_post'])->name('update_post');
    Route::get('/unseen_posts',[CommunityController::class,'unseen_posts'])->name('unseen_posts');
    Route::get('/post_comments/{id}',[CommunityController::class,'post_comments'])->name('post_comments');
    Route::post('/create_comment', [CommunityController::class, 'create_comment'])->name('create_comment');
    Route::get('/delete_comment', [CommunityController::class, 'delete_comment'])->name('delete_comment');
    Route::post('/update_comment', [CommunityController::class, 'update_comment'])->name('update_comment');
    Route::get('/delete_replay', [CommunityController::class, 'delete_replay'])->name('delete_replay');
    Route::post('/create_replay', [CommunityController::class, 'create_replay'])->name('create_replay');
    Route::post('/update_replay', [CommunityController::class, 'update_replay'])->name('update_replay');
    //////////////////////////////////////////////
    Route::get('/settings',[SettingController::class,'index'])->name('settings');
    Route::get('/setting/edit/{id}', [SettingController::class, 'edit_setting'])->name('edit.setting');
    Route::post('/setting/update/{id}', [SettingController::class, 'update_setting'])->name('update.setting');
});