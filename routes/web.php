<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
Route::get('/', function () {
    return view('welcome');
});

*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/account/{id}', 'AccountsController@index');

Route::get('/home', 'HomeController@index')->name('home');

// resource used for RESTful controller
//$user_id = auth()->user()->id;
Route::resource('withdrawal', 'WithdrawalsController');
Route::resource('deposit', 'DepositsController');
Route::resource('transfer', 'TransfersController');
