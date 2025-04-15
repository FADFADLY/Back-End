<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use ApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'gender'       => 'required|string',
            'age'          => 'required|int',
            'password'     => 'required|string|min:6|confirmed',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب ان يكون نص',
            'name.max' => 'الاسم يجب ان لا يتجاوز 255 حرف',
            'username.required' => 'اسم المستخدم مطلوب',
            'username.unique' => 'اسم المستخدم مستخدم من قبل',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'البريد الالكتروني يجب ان يكون صالح',
            'email.unique' => 'البريد الالكتروني مستخدم من قبل',
            'gender.required' => 'الجنس مطلوب',
            'age.required' => 'العمر مطلوب',
            'age.integer' => 'العمر يجب ان يكون رقم',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب ان لا تقل عن 6 احرف',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $allFields = array_keys($this->rules()); // كل الحقول المطلوبة في الفورم

        $errors = $validator->errors()->toArray(); // اللي حصل فيها error بس

        // نكمل الباقي ونخليها [] لو مفيش خطأ
        foreach ($allFields as $field) {
            if (!array_key_exists($field, $errors)) {
                $errors[$field] = [];
            }
        }
        throw new HttpResponseException(
            $this->errorResponse(
                $errors,
                'فشل في التحقق من البيانات',
                422
            )
        );
    }
}
