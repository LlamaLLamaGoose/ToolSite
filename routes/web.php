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
    return view('home');
});

Route::group(['prefix' => 'tools'], function () {
    Route::get('/', function () {
        return view('tools/home');
    });

    Route::get('/iplookup', function () {
        return view('tools/iplookup');
    });

    Route::get('/bulkip', function () {
        return view('tools/bulkip');
    });

    Route::post('/iplookup', 'ToolsController@ipcheck');

    Route::post('/bulkip', 'ToolsController@bulkip');


});
