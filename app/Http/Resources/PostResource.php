<?php

namespace App\Http\Resources;

use App\Enums\AttachmentTypeEnum;
use App\Models\Blog;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attachment = null;

        if ($this->type == AttachmentTypeEnum::POLL->value) {
            $options = $this->pollOptions()->select('id', 'option', 'votes')->get();

            $totalVotes = $options->sum('votes') ?: 0;


            $optionsData = $options->map(function ($option) use ($totalVotes) {
                $voted = PollVote::where('user_id', Auth::id())
                    ->where('poll_option_id', $option->id)
                    ->exists();
                return [
                    'id' => $option->id,
                    'option' => $option->option,
                    'votes' => $option->votes,
                    'percentage' => $totalVotes !== 0 ? round(($option->votes / $totalVotes) * 100, 2) : 0,
                    'voted' => $voted,
                ];
            });

            $attachment = [
                'options' => $optionsData,
                'total_votes' => $totalVotes,
            ];
        }

        if ($this->type == AttachmentTypeEnum::LOCATION->value) {
            $attachment = $this->location()->select('id', 'latitude', 'longitude', 'label')->first();
        }

        if (in_array($this->type, [
            AttachmentTypeEnum::IMAGE->value,
            AttachmentTypeEnum::AUDIO->value,
            AttachmentTypeEnum::FILE->value
        ])) {
            $attachment = $this->attachment ? asset('/storage/' . $this->attachment) : null;
        }

        if ($this->type == AttachmentTypeEnum::ARTICLE->value) {
            $blog = Blog::where('id', (int)$this->attachment)->first();
            $attachment = new BlogResource($blog);
        }

        return [
            'id' => $this->id,
            'content' => $this->content,
            'type' => AttachmentTypeEnum::from($this->type)->label(),
            'attachment' => $attachment,
            'created_at' => $this->created_at->diffForHumans(),
            'user_name' => $this->user?->username,
            'user_avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            'comments_count' => $this->comments()->count(),
            'reactions_count' => $this->reactions()->count(),
            'reacted' => $this->reactions()->where('user_id', Auth::id())->exists(),
        ];
    }
}
