<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;
    protected $table = 'poll_options';

    protected $fillable = ['post_id', 'option', 'votes'];


    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
