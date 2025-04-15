<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ApiResponse
{
    protected function successResponse($data = null, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors'=> null,
            'code' => $code
        ], $code);
    }

    protected function errorResponse($errors = null,$message = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'code' => $code
        ], $code);
    }

    public function validationErrorResponse($exception, array $allFields, $message ='فشل في التحقق من البيانات')
    {
        $errors = $exception->validator->errors()->toArray();

        // نضيف الحقول اللي مفيهاش أخطاء
        foreach ($allFields as $field) {
            if (!isset($errors[$field])) {
                $errors[$field] = [];
            }
        }

        return $this->errorResponse(
            $errors,
            $message,
            422
        );
    }




}
