<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entry_date',
        'model_prediction',
        'notes',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeFilterByDate($query, Request $request)
    {
        if ($request->has('month')) {
            $month = (int) $request->input('month');
            $year = (int) $request->input('year', now()->year);

            $query->whereMonth('entry_date', $month)
                ->whereYear('entry_date', $year);
        }

        if ($request->has('week')) {
            $week = (int) $request->input('week');
            $year = (int) $request->input('year', now()->year);

            $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = Carbon::now()->setISODate($year, $week)->endOfWeek();

            $query->whereBetween('entry_date', [$startOfWeek, $endOfWeek]);
        }

        return $query;
    }


}
