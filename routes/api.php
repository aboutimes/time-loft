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

Route::group([
//    'prefix'=>'/v1',    // 路由前缀
//    'middleware'=>['auth:api'], // 中间件
    'namespace' => '\Api',  // 命名空间
    ], function () {
    Route::post('/login','Authenticate@login');
    Route::post('/register','Authenticate@register');
    Route::post('/logout','Authenticate@logout');
    //测试路由
    Route::get('test', function (){
        $a =  'test';
        $users = App\User::find(1);
        return response()->json($a);
//        return response()->json($users);
    });
});

Route::group([
//    'prefix'=>'/v1',    // 路由前缀，版本 v1
//    'middleware'=>['auth:api'], // 中间件
    'namespace' => '\Api',  // 命名空间
    ], function () {
    // 用户
    Route::get('users', 'UserController@index');
    Route::get('user/{id}', 'UserController@show');
    // 文章
    Route::get('articles', 'ArticleController@index');
    Route::get('article/{id}', 'ArticleController@show');
    // 分类
    Route::get('categories', 'CategoryController@index');
    Route::get('category/{id}', 'CategoryController@show');
    // 标签
    Route::get('tags', 'TagController@index');
    Route::get('tag/{id}', 'TagController@show');
    // 足迹
    Route::get('footprints', 'FootprintController@index');
    Route::get('footprint/{id}', 'FootprintController@show');
});
