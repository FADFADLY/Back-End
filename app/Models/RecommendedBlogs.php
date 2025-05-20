<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedBlogs extends Model
{
    protected $fillable = ['user_id', 'recommendations'];

    protected $casts = [
        'recommendations' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
