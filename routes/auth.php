<?php

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
Route::post('posts/{post}/upvote', [
    'uses' => 'VotePostController@upvote',
]);

Route::post('posts/{post}/downvote', [
    'uses' => 'VotePostController@downvote',
]);

Route::delete('posts/{post}/vote', [
    'uses' => 'VotePostController@undovote',
]);

// Comment

Route::post('comments/{comment}/upvote', [
    'uses' => 'VoteCommentController@upvote',
]);

Route::post('comments/{comment}/downvote', [
    'uses' => 'VoteCommentController@downvote',
]);

Route::delete('comments/{comment}/vote', [
    'uses' => 'VoteCommentController@undovote',
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
