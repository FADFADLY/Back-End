<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'description' => $this['description'],
            'audio' => $this['audio'],
            'duration_minutes' => round($this['audio_length_sec'] ?? $this['duration_minutes'], 0),
            'published_at' => $this['published_at'],
            'image' => $this['image'],
            'url' => $this['listennotes_url'] ?? $this['url'],
            'podcast' => [
                'id' => $this['podcast']['id'] ?? null,
                'title' => $this['podcast']['title'] ?? null,
                'publisher' => $this['podcast']['publisher'] ?? null,
                'image' => $this['podcast']['image'] ?? null,
            ],
        ];
    }
}
