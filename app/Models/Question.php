<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question', 'test_id'];

    public function test()
    {
        return $this->belongsTo('Test');
    }

    public function answers()
    {
        return $this->hasMany('Answer');
    }

}
