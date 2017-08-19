<?php

Route::post('logout', function () {
    Auth::logout();

    return redirect('/');
});

// Posts
Route::get('posts/create', [
    'uses' => 'CreatePostController@create',
    'as' => 'posts.create',
]);

Route::post('posts/create', [
    'uses' => 'CreatePostController@store',
    'as' => 'posts.store',
]);

// Votes
Route::pattern('module', '[a-z]+');

Route::bind('votable', function ($votableId, $route) {
    $modules = [
        'posts' => \App\Post::class,
        'comments' => \App\Comment::class,
    ];

    $model = $modules[$route->parameter('module')] ?? abort(404);

    return $model::findOrFail($votableId);

});

Route::post('{module}/{votable}/upvote', [
    'uses' => 'VoteController@upvote',
]);

Route::post('{module}/{votable}/downvote', [
    'uses' => 'VoteController@downvote',
]);

Route::delete('{module}/{votable}/vote', [
    'uses' => 'VoteController@undovote',
]);

Route::post('posts/{post}/comment', [
    'uses' => 'CommentController@store',
    'as' => 'comments.store',
]);

Route::post('comments/{comment}/accept', [
    'uses' => 'CommentController@accept',
    'as' => 'comments.accept',
]);

Route::post('posts/{post}/subscribe', [
    'uses' => 'SubscriptionController@subscribe',
    'as' => 'posts.subscribe'
]);

Route::delete('posts/{post}/unsubscribe', [
    'uses' => 'SubscriptionController@unSubscribe',
    'as' => 'posts.unsubscribe'
]);

Route::get('mis-posts/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.mine',
]);
