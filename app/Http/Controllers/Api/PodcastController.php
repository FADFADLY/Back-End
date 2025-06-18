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
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 4);

        $params = [
            'q' => 'podcast',
            'type' => 'episode',
            'offset' => ($page - 1) * $perPage,
            'len_min' => 1,
            'sort_by_date' => 1,
        ];

        $response = Http::withHeaders([
            'X-ListenAPI-Key' => config('services.listennotes.key'),
        ])->get('https://listen-api.listennotes.com/api/v2/search', $params);

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch podcasts'], 500);
        }

        $data = $response->json();

        $formatted = collect($data['results'])->map(function ($item) {
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

        return response()->json([
            'count' => $data['count'] ?? $formatted->count(),
            'total' => $data['total'] ?? null,
            'next_offset' => $data['next_offset'] ?? null,
            'results' => $formatted,
        ]);
    }
}
