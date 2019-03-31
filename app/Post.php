<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'anons', 'text', 'tags', 'image', 'datatime'];

    public $timestamps = false;

    protected $casts = [
        'tags' => 'array',
    ];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}

