<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\RecommendedBlogs;
use Illuminate\Support\Facades\Http;

class BlogRecommendationService
{
    protected string $apiUrl = 'https://ahmedelsherbeny-blog-recommendation.hf.space/recommend';

    public function generateRecommendations(string $description): void
    {
        try {
            $response = Http::post($this->apiUrl, [
                'query' => $description,
                'top_k' => 6,
            ]);

            if ($response->successful()) {
                $recommendations = $response->json()['recommendations'] ?? [];

                $blogIds = Blog::whereIn('article_id', $recommendations)
                    ->pluck('id')
                    ->toArray();

                RecommendedBlogs::updateOrCreate(
                    ['user_id' => auth()->id()],
                    ['recommendations' => json_encode($blogIds)]
                );
            }
        } catch (\Exception $e) {
            // يُفضل تخزين الخطأ في اللوج أو تجاهله في هذه الخدمة
        }
    }

    public function getOrderedBlogs()
    {
        $user = auth()->user();
        $recommendedIds = json_decode($user->recommendedBlogs?->recommendations ?? '[]', true);

        $recommendedBlogs = collect();

        if (!empty($recommendedIds)) {
            $recommendedBlogs = Blog::whereIn('id', $recommendedIds)
                ->orderByRaw('FIELD(id, ' . implode(',', $recommendedIds) . ')')
                ->get();
        }

        $otherBlogs = Blog::whereNotIn('id', $recommendedIds)
            ->latest()
            ->get();

        return $recommendedBlogs->concat($otherBlogs);
    }
}
