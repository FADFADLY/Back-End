<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BookResource extends JsonResource
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
            'cover' => $this->image_url ? asset('/storage/' . $this->image_url) : null,

            $this->mergeWhen(
                $request->routeIs('books.show'),
                [
                    'author' => $this->author,
                    'genre' => $this->genre,
                    'publisher' => $this->publisher,
                    'publication_date' => $this->publication_date,
                    'pages_count' => $this->pages_count,
                    'description' => $this->description,
                    'reacted' => $this->reactions()->where('user_id', Auth::id())->exists(),
                ]
            )

        ];
    }
}
