<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class BookController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $books = Book::all();

        return $this->successResponse(
            $books,
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
            $book,
            'تم جلب بيانات الكتاب بنجاح',
            200
        );
    }
}
