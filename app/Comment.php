<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['datatime', 'author', 'comment'];

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('App\Post', 'foreign_key');
    }
}
