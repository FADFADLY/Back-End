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
        $grouped = $episodes->groupBy('podcast_id')->map(function ($group, $podcastId) {
            $first = $group->first();
            return [
                'podcast_id' => $podcastId,
                'podcast_name' => $first['podcast_name'],
                'publisher' => $first['publisher'],
                'image' => $first['image'],
                'episodes_count' => $group->count(),
            ];
        })->values(); // reset numeric keys


        return response()->json([
            'count' => $grouped->count(),
            'results' => $grouped,
        ]);
    }
    public function show($id, Request $request)
    {
        $params = [];

        if ($request->filled('next_episode_pub_date')) {
            $params['next_episode_pub_date'] = (int) $request->get('next_episode_pub_date');
        }

        if ($request->filled('sort')) {
            $params['sort'] = $request->get('sort'); // 'recent_first' or 'oldest_first'
        }

        $response = Http::withHeaders([
            'X-ListenAPI-Key' => config('services.listennotes.key'),
        ])->get("https://listen-api.listennotes.com/api/v2/podcasts/{$id}", $params);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات.',
                'data' => null,
                'error' => $response->body(),
                'code' => $response->status(),
            ]);
        }

        $data = $response->json();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب بيانات البودكاست بنجاح.',
            'data' => [
                'podcast_id' => $data['id'],
                'title' => $data['title'],
                'publisher' => $data['publisher'],
                'image' => $data['image'],
                'total_episodes' => $data['total_episodes'],
                'next_episode_pub_date' => $data['next_episode_pub_date'] ?? null,
                'episodes' => collect($data['episodes'])->map(function ($episode) {
                    return [
                        'id' => $episode['id'],
                        'title' => $episode['title'],
                        'description' => $episode['description'],
                        'audio' => $episode['audio'],
                        'duration_minutes' => round($episode['audio_length_sec'] / 60),
                        'published_at' => \Carbon\Carbon::createFromTimestampMs($episode['pub_date_ms'])->toDateTimeString(),
                        'image' => $episode['image'],
                        'url' => $episode['listennotes_url'],
                    ];
                })->values()
            ],
            'code' => 200,
            'error' => null
        ]);
    }

    public function episode($id, Request $request)
    {
        $params = [];

        // Optional: handle show_transcript param (if needed in future)
        if ($request->boolean('show_transcript')) {
            $params['show_transcript'] = 1;
        }

        $response = Http::withHeaders([
            'X-ListenAPI-Key' => config('services.listennotes.key'),
        ])->get("https://listen-api.listennotes.com/api/v2/episodes/{$id}", $params);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الحلقة.',
                'data' => null,
                'error' => $response->body(),
                'code' => $response->status(),
            ]);
        }

        $data = $response->json();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب بيانات الحلقة بنجاح.',
            'data' => [
                'id' => $data['id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'audio' => $data['audio'],
                'duration_minutes' => round($data['audio_length_sec'] / 60),
                'published_at' => Carbon::createFromTimestampMs($data['pub_date_ms'])->toDateTimeString(),
                'image' => $data['image'],
                'url' => $data['listennotes_url'],
                'podcast' => [
                    'id' => $data['podcast']['id'] ?? null,
                    'title' => $data['podcast']['title'] ?? null,
                    'publisher' => $data['podcast']['publisher'] ?? null,
                    'image' => $data['podcast']['image'] ?? null,
                ]
            ],
            'code' => 200,
            'error' => null
        ]);
    }
}
