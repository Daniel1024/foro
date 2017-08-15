<?php

use App\{
    Mail\TokenMail, Token, User
};
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{

    public function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('admin@daniel.net', 'email')
            ->type('daniel1024', 'username')
            ->type('Daniel', 'first_name')
            ->type('López', 'last_name')
            ->press('Regístrate');

        $this->seeInDatabase('users', [
            'email' => 'admin@daniel.net',
            'username' => 'daniel1024',
            'first_name' => 'Daniel',
            'last_name' => 'López',
        ]);

        $user = User::query()
            ->first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id,
        ]);

        $token = Token::query()
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id == $token->id;
        });

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu correo electrónico un enlace para que inicies sesión');
    }

    public function test_a_user_cannnot_create_an_account_with_empty_data()
    {
        $this->visitRoute('register')
            ->press('Regístrate')
            ->seeRouteIs('register')
            ->seeErrors([
                'email' => 'El campo correo electrónico es obligatorio',
                'username' => 'El campo usuario es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio',
            ]);
    }

    public function test_a_user_cannnot_create_an_account_with_wrong_email()
    {
        $this->visitRoute('register')
            ->type('daniel@admin', 'email')
            ->type('daniel1024', 'username')
            ->type('Daniel', 'first_name')
            ->type('López', 'last_name')
            ->press('Regístrate')
            ->seeRouteIs('register')
            ->seeErrors([
                'email' => 'correo electrónico no es un correo válido',
            ]);
    }
}
