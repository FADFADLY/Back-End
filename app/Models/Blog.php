<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'body',
        'image',
        'author',
        'views_count',
        'likes_count',
        'share_count',
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }
}
