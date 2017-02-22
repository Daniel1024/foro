<?php

namespace App;

use App\Mail\TokenMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{
    Auth, Mail
};

class Token extends Model
{
    protected $guarded = [];

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

        $token->user()->associate($user);

        $token->save();

        return $token;
    }

    public static function findActive(string $token)
    {
        return static::query()
            ->where('token', $token)
            ->where('created_at', '>=', Carbon::parse('-30 minutes'))
            ->first();
    }

    public function sendByEmail()
    {
        Mail::to($this->user)->send(new TokenMail($this));
    }

    public function login()
    {
        Auth::login($this->user);

        $this->delete();
    }

    public function getUrlAttribute()
    {
        return route('login', $this->token);
    }
}
