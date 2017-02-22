<?php

use App\Notifications\PostCommented;
use App\User;

class NotifyUsersTest extends FeatureTestCase
{
    function test_the_subscibers_receive_a_notification_when_post_is_commented()
    {
        Notification::fake();

        $post = $this->createPost();

        $subscriber = $this->defaultUser();

        $subscriber->subscribeTo($post);

        $writer = factory(User::class)->create();

        $writer->subscribeTo($post);

        $comment = $writer->comment($post, 'Un comentario cualquiera');

        Notification::assertSentTo(
            $subscriber,
            PostCommented::class,
            function ($notification) use ($comment) {
                return $notification->comment->id == $comment->id;
            }
        );

        Notification::assertNotSentTo($writer, PostCommented::class);
    }
}
