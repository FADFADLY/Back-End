<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{

    protected $fillable = ['user_id', 'reactable_id', 'reactable_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reactable()
    {
        return $this->morphTo();
    }
}
