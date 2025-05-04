<?php

namespace App\Jobs;

use App\Models\Post;
use App\Notifications\ContentAnalysisNotification;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class AnalyzeContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels, ApiResponse;

    protected $postId;
    public $modelUrl;

    public function __construct($postId, $modelUrl)
    {
        $this->postId = $postId;
        $this->modelUrl = $modelUrl;
    }

    public function handle()
    {
        try {

            $post = Post::find($this->postId); // الحصول على البوست باستخدام ID

            if (!$post) {
                return $this->errorResponse([], 'المنشور غير موجود', 404);
            }

            $response = Http::post($this->modelUrl, [
                'data' => [$post->content]
            ]);

            if (!$response->ok()) {
                return $this->errorResponse([], 'فشل في الاتصال بالنموذج', 500);
            }

            $eventId = $response->json('event_id');
            if (!$eventId) {
                return $this->errorResponse([], 'فشل في الحصول على معرف الحدث', 500);
            }

            $resultResponse = Http::get("{$this->modelUrl}/{$eventId}");
            if (!$resultResponse->ok()) {
                return $this->errorResponse([], 'فشل في الحصول على نتيجة التحليل', 500);
            }

            $resultText = $resultResponse->body();

            if (str_contains($resultText, 'negative')) {
                $post->update(['status' => 'rejected']);
                $message = 'تم رفض المنشور بسبب احتوائه على محتوى غير لائق أو سلبي';
                $status = 'rejected';
            } else {
                $post->update(['status' => 'approved']);
                $message = 'تم تحليل المحتوى بنجاح';
                $status = 'approved';
            }

            $user = $post->user;
            if ($user) {
                $user->notify(new ContentAnalysisNotification($status, $message));
            }

        } catch (\Exception $e) {
            return $this->errorResponse([], 'حدث خطأ أثناء معالجة المحتوى', 500);
        }
    }
}
