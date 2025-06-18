<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PodcastController extends Controller
{
    public function index(Request $request)
    {
        // Get pagination values from the request or set default values
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 4);

        // Prepare query parameters for ListenNotes API
        $params = [
            'q' => 'podcast', // Search query
            'type' => 'episode', // Search type (episodes only)
            'offset' => ($page - 1) * $perPage, // Offset based on page number
            'len_min' => 1, // Minimum length in minutes
            'sort_by_date' => 1, // Sort results by date descending
        ];

        // Make GET request to ListenNotes API
        $response = Http::withHeaders([
            'X-ListenAPI-Key' => config('services.listennotes.key'),
        ])->get('https://listen-api.listennotes.com/api/v2/search', $params);

        // If request failed, return error
        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch podcasts'], 500);
        }

        // Get the response as array
        $data = $response->json();

        // Format the results
        $formatted = collect($data['results'])
            ->slice(0, $perPage) // Limit the number of results manually
            ->map(function ($item) {
                return [
                    'title' => $item['title_original'],
                    'description' => $item['description_original'],
                    'audio' => $item['audio'],
                    'duration_minutes' => round($item['audio_length_sec'] / 60),
                    'published_at' => Carbon::createFromTimestampMs($item['pub_date_ms'])->toDateTimeString(),
                    'image' => $item['image'],
                    'podcast_name' => $item['podcast']['title_original'] ?? null,
                    'publisher' => $item['podcast']['publisher_original'] ?? null,
                    'url' => $item['listennotes_url'],
                ];
            });

        // Return paginated response
        return response()->json([
            'count' => $formatted->count(), // Count of returned items
            'total' => $data['total'] ?? null, // Total from ListenNotes
            'next_offset' => $data['next_offset'] ?? null, // Offset for next page (if using offset-based pagination)
            'results' => $formatted, // Formatted results
        ]);
    }
}
