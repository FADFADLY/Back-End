<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'question' => $this->question,
            'test_id' => (int) $this->test_id,
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
