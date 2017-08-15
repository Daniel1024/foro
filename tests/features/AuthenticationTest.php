<?php

use App\Token;

class AuthenticationTest extends FeatureTestCase
{
    public function test_a_user_can_login_with_a_token_url()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $this->visitRoute('login', $token->token);

        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens', [
            'id' => $token->id,
        ]);

        $this->seePageIs('/');
    }
}
