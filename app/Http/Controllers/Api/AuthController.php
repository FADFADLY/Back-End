<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendResetCodeJob;
use App\Models\ResetCode;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'gender'       => $request->gender,
            'age'          => $request->age,
            'password'     => Hash::make($request->password),
        ]);

        if(!$user){
            return $this->errorResponse('حدث خطأ اثناء انشاء الحساب', 500);
        }

        return $this->successResponse(
            null,
            'تم التسجيل بنجاح.',
            201
        );

    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ],[
                'email.required' => 'البريد الالكتروني مطلوب',
                'email.email' => 'البريد الالكتروني يجب ان يكون صالح',
                'password.required' => 'كلمة المرور مطلوبة',
            ]
        );

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('البريد الالكتروني او كلمة المرور غير صحيحة', 401);
        }

        $user = User::where('email', $request->email)->first();

        try {
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse(
                [
                    'token' => $token
                ],
                'تم تسجيل الدخول بنجاح'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في تسجيل الدخول', 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ],[
                'email.required' => 'البريد الالكتروني مطلوب',
                'email.email' => 'البريد الالكتروني يجب ان يكون صالح',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('البريد الالكتروني غير موجود', 404);
        }

        $resetCode = rand(1000, 9999);

       ResetCode::create([
            'user_id' => $user->id,
            'code' => $resetCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        SendResetCodeJob::dispatchSync($user->email, $resetCode);

        return $this->successResponse(
            null,
            'تم ارسال رمز التحقق الى بريدك الالكتروني'
        );
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:4',
        ], [
            'code.required' => 'رمز التحقق مطلوب',
            'code.numeric' => 'رمز التحقق يجب ان يكون ارقام',
            'code.digits' => 'رمز التحقق يجب أن يكون 4 أرقام',
        ]);

        try {
            $resetCode = ResetCode::where('code', $request->code)
                ->where('is_used', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$resetCode) {
                return $this->errorResponse('رمز التحقق غير صحيح او منتهي الصلاحية', 400);
            }

            $user = User::find($resetCode->user_id);
            if (!$user) {
                return $this->errorResponse('المستخدم غير موجود', 404);
            }

            // Mark the code as used
            $resetCode->is_used = true;
            $resetCode->used_at = Carbon::now();
            $resetCode->save();

            return $this->successResponse(
                null,
                'تم التحقق بنجاح، يمكنك الآن إعادة تعيين كلمة المرور'
            );
        }catch (\Exception $e){
            return $this->errorResponse('فشل في التحقق من رمز التحقق', 500);
        }
    }

    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ],[
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'البريد الالكتروني يجب ان يكون صالح',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('البريد الالكتروني غير موجود', 404);
        }

        $resetCode = rand(1000, 9999);

        ResetCode::create([
            'user_id' => $user->id,
            'code' => $resetCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        SendResetCodeJob::dispatch($user->email, $resetCode);

        return $this->successResponse(
            null,
            'تم ارسال رمز التحقق الى بريدك الالكتروني'
        );
    }
    public function resetPassword(Request $request)
    {
      $validated =  $request->validate([
            'email'      => 'required|email',
            'password'   => 'required|string|min:8|confirmed',
        ],
        [
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'البريد الالكتروني يجب ان يكون صالح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب ان تكون 8 احرف على الاقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
        ]);


        try {
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return $this->errorResponse('المستخدم غير موجود', 404);
            }

            $user->password = Hash::make($validated['password']);
            $user->save();

            return $this->successResponse(null, 'تم إعادة تعيين كلمة المرور بنجاح');

        }catch (\Exception $e){
            return $this->errorResponse('فشل في إعادة تعيين كلمة المرور', 500);
        }

    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'تم تسجيل الخروج بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في تسجيل الخروج', 500);
        }
    }

    public function getUser()
    {
        $users  = User::all();

        if (!$users) {
            return $this->errorResponse('المستخدم غير موجود', 404);
        }

        return $this->successResponse($users, 'تمت العملية بنجاح');
    }
}
