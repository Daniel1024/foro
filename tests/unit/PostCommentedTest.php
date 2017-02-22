<?php

use App\{
    Comment, Post, User
};
use App\Notifications\PostCommented;
use Illuminate\Notifications\Messages\MailMessage;

class PostCommentedTest extends TestCase
{

    /**
     * @test
     */
    function it_builds_a_mail_message()
    {
        $post = new Post([
            'title' => 'Titulo del post'
        ]);
        $author = new User([
            'name' => 'Daniel López',
        ]);
        $comment = new Comment;
        $comment->post = $post;
        $comment->user = $author;

        $notication = new PostCommented($comment);

        $subscriber = new User();

        $message = $notication->toMail($subscriber);

        $this->assertInstanceOf(MailMessage::class, $message);

        $this->assertSame(
            'Nuevo comentario en: Titulo del post',
            $message->subject
        );

        $this->assertSame(
            'Daniel López escribió un comentario en: Titulo del post',
            $message->introLines[0]
        );

        $this->assertSame(
            $comment->post->url,
            $message->actionUrl
        );
    }
}
