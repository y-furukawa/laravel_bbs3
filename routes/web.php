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

Route::get('/', function () {
    return view('welcome');
});

Route::get('bbs', 'PostsController@index');

//第三引数に only を使うと、ホワイトリストとしてメソッドを指定できる
Route::resource('bbs', 'PostsController', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]);



//コメント投稿用
Route::resource('comment', 'CommentsController', ['only' => ['store']]);
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
