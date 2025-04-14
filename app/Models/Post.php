<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'type',
        'attachment',
    ];

    protected $casts = [
        'attachment' => 'array',
        'type' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function location()
    {
        return $this->hasOne(PostLocation::class);
    }

    public function pollOptions()
    {
        return $this->hasMany(PollOption::class);
    }
}
