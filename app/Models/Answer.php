<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model 
{

    protected $table = 'answers';
    public $timestamps = true;
    protected $fillable = array('answer', 'points');
    protected $visible = array('question_id');

    public function question()
    {
        return $this->belongsTo('Questions');
    }

}