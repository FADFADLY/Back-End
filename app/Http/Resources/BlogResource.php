<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       if(!str_contains($this->image, 'http')) {
            $this->image = asset('/storage/' . $this->image);
        }

        $converted = strtr($this->publish_date, [
            'يناير' => 'January', 'فبراير' => 'February', 'مارس' => 'March',
            'أبريل' => 'April', 'مايو' => 'May', 'يونيو' => 'June',
            'يوليو' => 'July', 'أغسطس' => 'August', 'سبتمبر' => 'September',
            'أكتوبر' => 'October', 'نوفمبر' => 'November', 'ديسمبر' => 'December',
        ]);

        $publish_date = Carbon::createFromFormat('d F Y', $converted);


        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'image' => $this->image ?? null,
            'created_at' => $publish_date->format('dMY'),
            'views' =>(int) $this->views_count,
            'likes' => $this->reactions()->count(),
            'share' =>(int) $this->share_count,
            'reacted' => $this->reactions()->where('user_id', Auth::id())->exists(),


            $this->mergeWhen(
                $request->routeIs('blogs.index'),
                [
                    'summary' => collect(explode("\n", $this->body))
                        ->take(3)
                        ->implode("\n"),
                ]
            ),

            $this->mergeWhen(
                $request->routeIs('blogs.show'),
                [
                    'body' => $this->body,
                ]
            )

        ];
    }
}
