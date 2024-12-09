<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{

    protected $fillable = ['user_id'];

    public function reactable()
    {
        return $this->morphTo();
    }

}
