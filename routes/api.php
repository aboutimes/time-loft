<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=>'/v1'], function () {
//    Route::post('/login','Authenticate@login');
    Route::post('/register','Authenticate@register');
    Route::post('/logout','Authenticate@logout');
    //测试路由
    Route::get('test', function (){
        $a =  'test';
        return response()->json(\App\User::pluck('id')->random());
    });
});

Route::group(['prefix'=>'/v1', 'middleware'=>['auth:api']], function () {
    Route::get('users', 'UserController@index');
    Route::get('user/{id}', 'UserController@show');
    Route::get('articles', 'ArticleController@index');
    Route::get('article/{id}', 'ArticleController@show');
    Route::get('categories', 'CategoryController@index');
    Route::get('category/{id}', 'CategoryController@show');
});
