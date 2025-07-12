<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

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
            'password' => ['required', 'string', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
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
            'password.required'   => 'كلمة المرور مطلوبة',
            'password.string'     => 'كلمة المرور يجب أن تكون نصًا',
            'password.min'        => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل',
            'password.confirmed'  => 'كلمة المرور غير متطابقة',
            'password.mixed'      => 'كلمة المرور يجب أن تحتوي على حرف كبير وحرف صغير على الأقل',
            'password.numbers'    => 'كلمة المرور يجب أن تحتوي على رقم واحد على الأقل',
            'password.symbols'    => 'كلمة المرور يجب أن تحتوي على رمز خاص مثل @ أو # أو $',
            'password.uncompromised' => 'كلمة المرور هذه تم اكتشافها في تسريبات بيانات، يرجى اختيار كلمة مرور مختلفة',
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
