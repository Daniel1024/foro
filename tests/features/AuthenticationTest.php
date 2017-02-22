<?php

use App\{Token};

class AuthenticationTest extends FeatureTestCase
{
    function test_a_user_can_login_with_a_token_url()
    {
        // Having
        $user = $this->defaultUser();

        $token =  Token::generateFor($user);

        // When
        $this->visitRoute('login', $token->token);

        //then
        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens', [
            'id' => $token->id,
        ]);

        $this->seePageIs('/');
    }
}
