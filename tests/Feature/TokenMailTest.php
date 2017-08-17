<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Mail\TokenMail;
use App\{
    Token, User
};
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

class TokenMailTest extends FeatureTestCase
{
    public function test_it_sends_a_link_with_the_token()
    {
        $user = new User([
            'first_name' => 'Daniel',
            'last_name' => 'López',
            'username' => 'dani',
            'email' => 'email@gmail.com'
        ]);

        $token = new Token([
            'token' => 'this-is-a-token',
            'user' => $user,
        ]);

        $token_url = route('login', ['token' => $token->token]);

        $this->open(new TokenMail($token))
            ->seeLink($token_url, $token_url);
    }

    protected function open(Mailable $mailable)
    {
        $transport = Mail::getSwiftMailer()
            ->getTransport();

        $transport->flush();

        Mail::send($mailable);


        $message = $transport
            ->messages()
            ->first();

        $this->crawler = new Crawler($message->getBody());

        return $this;
    }
}
