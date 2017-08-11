<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('posts/{post}', [
    'as' => 'posts.show',
    'uses' => 'PostController@show',
])->where('post', '\d+');
