<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'image' => $this->image ? asset('/storage/' . $this->image) : null,
            'created_at' => $this->created_at->format('dMY'),
            'views_count' => $this->views_count,
            'likes_count' => $this->likes_count,
            'share_count' => $this->share_count,

            $this->mergeWhen(
                $request->routeIs('blogs.show'),
                [
                    'body' => $this->body,
                ]
            )

        ];
    }
}
