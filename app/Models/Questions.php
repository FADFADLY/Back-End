<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model 
{

    protected $table = 'questions';
    public $timestamps = true;
    protected $fillable = array('question', 'test_id');

    public function test()
    {
        return $this->belongsTo('Test');
    }

    public function answers()
    {
        return $this->hasMany('Answer');
    }

}