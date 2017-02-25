<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;

        $this->attributes['slug'] = str_slug($value);
    }

    public function getUrlAttribute()
    {
        //return route('categories.show', [$this->id, $this->slug]);
        return '';
    }

}
