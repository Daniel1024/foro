<?php

namespace App\Http\Controllers;

use App\Token;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(string $token)
    {

        $token = Token::findActive($token);
        //dd(Token::query()->orderBy('id', 'DESC')->first()->created_at >= Carbon::parse('30 minutes'));

        if ($token == null) {
            alert('Este enlace ya expiró, por favor solicita otro', 'danger');

            return redirect()->route('token');
        }

        $token->login();

        return redirect('/');
    }
}
