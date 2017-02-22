<?php

use App\{
    Mail\TokenMail, Token, User
};
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('daniel@admin.com', 'email')
            ->type('daniel1024', 'username')
            ->type('Daniel', 'first_name')
            ->type('López', 'last_name')
            ->press('Regístrate');

        $this->seeInDatabase('users',[
            'email' => 'daniel@admin.com',
            'username' => 'daniel1024',
            'first_name' => 'Daniel',
            'last_name' => 'López',
        ]);

        $user = User::query()->where('email', 'daniel@admin.com')->first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id,
        ]);

        $token = Token::query()->where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id == $token->id;
        });

        $this->seeRouteIs('register')
            //->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesión');
    }
}
