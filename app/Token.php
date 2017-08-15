<?php

namespace App;

use App\Mail\TokenMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Token extends Model
{
    protected $fillable = ['token', 'user_id', 'ip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    public static function generateFor(User $user)
    {
        $token = new static;
        $token->token = str_random(60);
        $token->ip = request()->ip();

        $token->user()->associate($user);
        $token->save();

        return $token;
    }

    public function sendByEmail()
    {
        Mail::to($this->user)->send(new TokenMail($this));
    }
}
