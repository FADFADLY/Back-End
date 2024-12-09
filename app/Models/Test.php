<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model 
{

    protected $table = 'tests';
    public $timestamps = true;
    protected $fillable = array('name');

    public function questions()
    {
        return $this->hasMany('Questions');
    }

}