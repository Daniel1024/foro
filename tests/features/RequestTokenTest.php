<?php

use App\Mail\TokenMail;
use App\Token;
use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{
    public function test_a_guest_user_can_request_a_token()
    {
        Mail::fake();

        $user = $this->defaultUser(['email' => 'admin@daniel.net']);

        // When
        $this->visitRoute('token')
            ->type('admin@daniel.net', 'email')
            ->press('Solicitar token');

        // then: a new token is created in the database
        $token = Token::query()->where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id === $token->id;
        });

        $this->dontSeeIsAuthenticated();

        $this->seeRouteIs('login_confirmation')
            ->see('Enviamos a tu correo electrónico un enlace para que inicies sesión');

    }

    public function test_a_guest_user_cannot_request_a_token_without_an_email()
    {
        Mail::fake();

        $this->visitRoute('token')
            ->press('Solicitar token');

        $token = Token::query()
            ->first();

        $this->assertNull($token);

        Mail::assertNotSent(TokenMail::class);

        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio',
        ]);
    }

    public function test_a_guest_user_cannot_request_a_token_an_invalid_email()
    {
        $this->visitRoute('token')
            ->type('admin', 'email')
            ->press('Solicitar token');

        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'Este '.trans('validation.attributes.email').' no es un correo válido.',
        ]);
    }

    public function test_a_guest_user_cannot_request_a_token_with_a_non_existent_email()
    {
        $this->defaultUser(['email' => 'admin@daniel.net']);

        // When
        $this->visitRoute('token')
            ->type('user@daniel.net', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico no se encuentra registrado en el sistema.',
        ]);

    }


}
