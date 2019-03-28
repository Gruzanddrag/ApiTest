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
    Route::get('/lol',  'PostController@lol');
    Route::post('/auth', 'ApiController@authUser');
    Route::middleware('isAuth')->group(function (){
        Route::post('/posts', 'PostController@create');
        Route::post('/bul', 'PostController@index');
        Route::post('/posts/{id}', 'PostController@edit');
        Route::get('/wantPost/{id}', function($id){
            return App\Post::find($id);
        });
    });
});