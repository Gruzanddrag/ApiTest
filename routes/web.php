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
*/




Route::prefix('api')->group(function(){
    Route::get('/home', function () {
        return view('addPost');
    });
    Route::post('/auth', 'ApiController@authUser');
    Route::post('/posts', 'PostController@create');
    Route::post('/bul', 'PostController@index');
});