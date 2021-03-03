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

    Route::get('/bulkscreen', function () {
        return view('tools/bulkscreen');
    });

    Route::get('/hostfile', function () {
        return view('tools/hostfile');
    });

    Route::get('/o365mxrecords', function () {
        return view('tools/o365mxrecords');
    });

    Route::get('/dnstool', function () {
        return view('tools/dnstool');
    });

    Route::post('/iplookup/check', 'ToolsController@ipcheck');

    Route::post('/bulkip/check', 'ToolsController@bulkip');

    Route::post('/bulkscreen/check', 'ToolsController@bulkscreen');

    Route::post('/hostfile/check', 'ToolsController@hostfile');

    Route::post('/o365mxrecords/check', 'ToolsController@o365mxrecords');

    Route::post('/dnstool/check', 'ToolsController@dnsTool');
});
