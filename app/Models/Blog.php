<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'article_id',
        'title',
        'body',
        'image',
        'author',
        'description',
        'views_count',
        'likes_count',
        'share_count',
        'publish_date'
    ];

    public static function find(string $id)
    {
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'blog_views')->withTimestamps();
    }

}
