<?php

Route::get('/home', 'HomeController@index');

Route::get('posts/{post}-{slug}', [
    'uses' => 'ShowPostController',
    'as' => 'posts.show',
])->where('post', '\d+');

Route::get('posts-pendientes/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.pending',
]);

Route::get('posts-completados/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.completed',
]);

Route::get('{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.index',
]);
