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

        $episodes = collect($data['results'])
            ->slice(0, $perPage) // optional limit
            ->map(function ($item) {
                return [
                    'podcast_id' => $item['podcast']['id'] ?? null,
                    'podcast_name' => $item['podcast']['title_original'] ?? null,
                    'publisher' => $item['podcast']['publisher_original'] ?? null,
                    'image' => $item['podcast']['image'] ?? null,
                    'episode' => [
                        'title' => $item['title_original'],
                        'description' => $item['description_original'],
                        'audio' => $item['audio'],
                        'duration_minutes' => round($item['audio_length_sec'] / 60),
                        'published_at' => Carbon::createFromTimestampMs($item['pub_date_ms'])->toDateTimeString(),
                        'url' => $item['listennotes_url'],
                    ]
                ];
            });

        // Group episodes by podcast ID
        $grouped = $episodes->groupBy('podcast_id')->map(function ($group) {
            $first = $group->first();
            return [
                'podcast_name' => $first['podcast_name'],
                'publisher' => $first['publisher'],
                'image' => $first['image'],
                'episodes_count' => $group->count(),
                'episodes' => $group->pluck('episode')->values()
            ];
        })->values(); // reset numeric keys

        return response()->json([
            'count' => $grouped->count(),
            'results' => $grouped,
        ]);
    }
}
