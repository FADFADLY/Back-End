<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Reaction;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $books = Book::all();

        if ($books->isEmpty()) {
            return $this->errorResponse([], 'لا توجد كتب متاحة', 404);
        }

        return $this->successResponse(
            BookResource::collection($books),
            'تم جلب قائمة الكتب بنجاح',
            200
        );
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->errorResponse([], 'الكتاب غير موجود', 404);
        }

        return $this->successResponse(
            new BookResource($book),
            'تم جلب بيانات الكتاب بنجاح',
            200
        );
    }
}
