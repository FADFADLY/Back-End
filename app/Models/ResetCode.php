<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'is_used',
        'used_at',
        'expires_at',
    ];
}
