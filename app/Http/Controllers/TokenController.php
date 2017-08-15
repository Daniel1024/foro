<?php

namespace App\Http\Controllers;

use App\Token;
use App\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function create()
    {
        return view('token.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
        ], [
            'email.exists' => 'Este :attribute no se encuentra registrado en el sistema.',
            'email.email' => 'Este :attribute no es un correo válido.',
        ]);

        $user = User::query()
            ->where('email', $request->get('email'))
            ->first();

        Token::generateFor($user)->sendByEmail();

        return redirect()->route('login_confirmation');
    }

    public function confirm()
    {
        return view('token.confirm');
    }
}
