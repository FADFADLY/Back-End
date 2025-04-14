<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLocation extends Model
{
    use HasFactory;

    protected $table = 'post_locations';
    protected $fillable = ['post_id', 'latitude', 'longitude','label'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
