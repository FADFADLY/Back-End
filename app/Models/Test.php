<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $fillable = ['name'];

    public function questions()
    {
        return $this->hasMany('Questions');
    }

}
