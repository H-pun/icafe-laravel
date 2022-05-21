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
    return view('welcome');
});

// Route::get('/404', function(){
//     return abort(403, 'Please open using LINE app!');
//  });
Route::view('notline', 'notline');

Route::view('liff-starter', 'liff.starter');

Route::view('register', 'liff.register');

Route::view('assignment', 'liff.assignment');

Route::view('subject', 'liff.subject');

Route::view('scan', 'liff.scan');

Route::view('topup', 'liff.topup');
