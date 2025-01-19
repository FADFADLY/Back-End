<?php

namespace App\Http\Controllers;

use App\Models\MoodEntry;
use Illuminate\Http\Request;

class MoodEntryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood'    => 'required|in:happy,neutral,sad,angry,crying',
            'feeling' => 'nullable|string',
            'notes'   => 'nullable|string',
        ]);

        $moodEntry = MoodEntry::create([
            'user_id'    => auth()->id(),
            'entry_date' => now()->toDateString(),
            'mood'       => $validated['mood'],
            'feeling'    => $validated['feeling'],
            'notes'      => $validated['notes'],
        ]);

        return response()->json(['message' => 'Mood entry saved successfully!', 'data' => $moodEntry], 201);
    }

    public function index(Request $request)
    {
        $query = MoodEntry::query();

        if ($request->has('month')) {
            $month = $request->input('month');
            $query->whereMonth('entry_date', $month);
        }

        if ($request->has('date')) {
            $date = $request->input('date');
            $query->whereDate('entry_date', $date);
        }

        if ($request->has('week')) {
            $week = $request->input('week');
            $month = $request->input('month'); // Get the month to filter by

            // Calculate the start and end of the week within the given month
            $startOfWeek = \Carbon\Carbon::now()->month($month)->setISODate(date('Y'), $week)->startOfWeek()->toDateString();
            $endOfWeek = \Carbon\Carbon::now()->month($month)->setISODate(date('Y'), $week)->endOfWeek()->toDateString();

            // Filter the entries that fall between the start and end of the week
            $query->whereBetween('entry_date', [$startOfWeek, $endOfWeek]);
        }

        $moodEntries = $query->get();

        // Add the day of the week for each entry to the response data
        $moodEntries->map(function ($entry) {
            $entry->day_of_week = \Carbon\Carbon::parse($entry->entry_date)->format('l'); // Get the full day name (e.g., Monday)
            return $entry;
        });

        // Return the results as a JSON response with day of the week
        return response()->json(['data' => $moodEntries], 200);
    }

    public function show($id)
    {
        // Find the mood entry by the given ID
        $moodEntry = MoodEntry::find($id);

        // If the mood entry is not found, return a 404 response with a message
        if (!$moodEntry) {
            return response()->json(['message' => 'Mood entry not found'], 404);
        }

        // Add the day of the week using the getDayOfWeek method
        $moodEntry->day_of_week = \Carbon\Carbon::parse($moodEntry->entry_date)->format('l');

        // Return the mood entry data along with the day of the week
        return response()->json(['data' => $moodEntry], 200);
    }
}
