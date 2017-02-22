<?php

use App\{
    Mail\TokenMail, Token
};
use Illuminate\Support\Facades\Mail;

class AuthenticationTest extends FeatureTestCase
{
    function test_a_guest_user_can_request_a_token()
    {
        Mail::fake();

        $email = 'd.lopez.1740@gmail.com';
        $user = $this->defaultUser(['email' => $email]);

        $this->visitRoute('token')
            ->type($email, 'email')
            ->press('Solicitar token');

        $token = Token::query()->where('user_id', $user->id)->first();

        $this->assertNotNull($token, 'El token no fue creado');

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id == $token->id;
        });

        $this->dontSeeIsAuthenticated();

        $this->see('Enviamos a tu email un enlace para que inicies sesión');

    }

    function test_a_guest_user_can_request_a_token_without_an_email()
    {
        Mail::fake();

        $this->visitRoute('token')
            ->press('Solicitar token');

        //$token = Token::query()->first();

        //$this->assertNull($token, 'El token fue creado');

        Mail::assertNotSent(TokenMail::class);

        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio'
        ]);

    }

    function test_a_guest_user_can_request_a_token_an_invalid_email()
    {
        $this->visitRoute('token')
            ->type('Daniel', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Correo electrónico no es un correo válido'
        ]);

    }

    function test_a_guest_user_can_request_a_token_with_a_non_existent_email()
    {
        $email = 'd1.lopez.1740@gmail.com';
        $this->defaultUser(['email' => $email]);

        $this->visitRoute('token')
            ->type('d24.lop@gmail.com', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico no existe'
        ]);

    }
}
