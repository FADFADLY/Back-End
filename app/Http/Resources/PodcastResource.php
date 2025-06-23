<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PodcastResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'podcast_id' => $this['podcast_id'] ?? $this['id'] ?? null,
            'title' => $this['podcast_name'] ?? $this['title'] ?? null,
            'publisher' => $this['publisher'] ?? null,
            'image' => $this['image'] ?? null,
            'total_episodes' => $this['total_episodes'] ?? null,
            'episodes_count' => $this['episodes_count'] ?? null,
            'listennotes_url' => $this['listennotes_url'] ?? null,
            'next_episode_pub_date' => $this['next_episode_pub_date'] ?? null,
        ];
    }
}
