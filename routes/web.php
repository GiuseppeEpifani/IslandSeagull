<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

//Content
Route::resource('content', App\Http\Controllers\Content\ContentController::class)->except(['show']);
Route::put('activeContent/{content}', App\Http\Controllers\Content\ActiveContentController::class);

//Species
Route::resource('specie', App\Http\Controllers\Specie\SpecieController::class)->except(['show', 'create']);
Route::resource('kind', App\Http\Controllers\Specie\KindController::class)->except(['show', 'create']);
Route::get('showKinds', App\Http\Controllers\Specie\ShowKindController::class);


//User
Route::resource('user', App\Http\Controllers\User\UserController::class)->only(['index', 'destroy', 'show', 'update']);
Route::put('activeUser/{user}', [App\Http\Controllers\User\ActionOnUserController::class,'activeUser']);
Route::put('deactiveUser/{user}', [App\Http\Controllers\User\ActionOnUserController::class, 'deactiveUser']);

Route::get('profile', function () {
    return view('User/profileUser');
});

});

Route::get('/showVerify', function () {
    return view('auth/verify');
});  
