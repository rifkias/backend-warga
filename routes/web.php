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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',function(){
    return response("Welcome Sistem Warga",200)->header('Content-Type', 'text/plain');
})->name("basePath");
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
