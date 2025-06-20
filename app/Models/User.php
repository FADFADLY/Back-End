<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'gender',
        'age',
        'password',
        'avatar',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function moodEntries()
    {
        return $this->hasMany(MoodEntry::class);
    }

    public function habitsScore()
    {
        return $this->hasOne(HabitsScore::class);
    }

    public function viewedBlogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_views')->withTimestamps();
    }

    public function recommendedBlogs()
    {
        return $this->hasOne(RecommendedBlogs::class);
    }

    public function chats()
    {
        return $this->hasMany(ChatbotChat::class);
    }

}
