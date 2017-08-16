<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;

        $this->attributes['slug'] = Str::slug($value);
    }
}
