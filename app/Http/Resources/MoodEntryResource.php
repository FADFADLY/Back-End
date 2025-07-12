<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoodEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $translatedMoods = [
            'none' => 'أشعر بشكل عادي.',
            'anger' => 'أشعر بالغضب.',
            'joy' => 'أشعر بالسعادة.',
            'sadness' => 'أشعر بالحزن.',
            'love' => 'أشعر بالحب.',
            'fear' => 'أشعر بالخوف.',
            'sympathy' => 'أشعر بالتعاطف.',
            'surprise' => 'أشعر بالدهشة.',
        ];

        return [
            'id' => $this->id,
            'entry_date' => $this->entry_date,
            'day_of_week' => Carbon::parse($this->entry_date)->format('D'),
            'mood' => $this->model_prediction,
            'feeling' => $translatedMoods[$this->model_prediction] ?? 'الشعور غير معروف.',
            'notes' => $this->notes,
        ];
    }


}
