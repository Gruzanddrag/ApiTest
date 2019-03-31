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
    Route::post('/lol',  'PostController@lol');
    Route::post('/auth', 'ApiController@authUser');
    Route::get('posts/tags/{tagName}', 'PostController@searchByTag');
    Route::middleware('isAuth')->group(function (){
        Route::post('/posts', 'PostController@create');
        Route::post('/bul', 'PostController@index');
        Route::post('/posts/{id}', 'PostController@edit');
        Route::get('/posts/{id}', 'PostController@show');
        Route::delete('/posts/{id}', 'PostController@destroy');
        Route::get('/posts', 'PostController@index');
        Route::post('posts/{post_id}/comments', 'CommentController@create');
        Route::delete('posts/{post_id}/comments/{comment_id}', 'CommentController@destroy');
    });
});