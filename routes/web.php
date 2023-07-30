<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

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
Route::group(['middleware' => 'auth.user'], function () {
    // Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home', [MainController::class, 'home'])->name('home');
    Route::get('/update-task/{id}', [MainController::class, 'updateTask'])->name('updateTask');
    Route::post('/save-task', [MainController::class, 'saveTask'])->name('saveTask');
    Route::get('/delete-task/{id}', [MainController::class, 'deleteTask'])->name('deleteTask');
    Route::post('/save-updated-task', [MainController::class, 'saveUpdatedTask'])->name('saveUpdatedTask');

    Route::post('/story-interact', [MainController::class, 'storyInteract'])->name('storyInteract');
    // Route::get('/get-suggestions', [MainController::class, 'getSuggestions'])->name('getSuggestions');
    Route::get('/get-suggestions', 'App\Http\Controllers\MainController@getSuggestions');

});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
