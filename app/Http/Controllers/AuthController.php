<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Laravel\Passport\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    use HasApiTokens;

    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ],[
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'البريد الالكتروني يجب ان يكون صالح',
            'password.required' => 'كلمة المرور مطلوبة',
            ]
        );

        // Attempt to log the user in
        if (!Auth::attempt($request->only('email', 'password'))) {
            return ApiResponse::sendResponse(401, 'البريد الالكتروني او كلمة المرور غير صحيحة', []);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Generate an access token for the user
        $token = $user->createToken('Access Token')->accessToken;

        // Return the token and user details
        return ApiResponse::sendResponse(200, 'تم تسجيل الدخول بنجاح',[
            'token' => $token,
            'username'  => $user->username,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'gender'       => 'required|string',
            'age'          => 'required|int',
            'password'     => 'required|string|min:6|confirmed',
        ],[
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
        ]);


        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'gender'       => $request->gender,
            'age'          => $request->age,
            'password'     => Hash::make($request->password),
        ]);

        if(!$user){
            return ApiResponse::sendResponse(400, 'حدث خطأ ما', []);
        }

        $token = $user->createToken('Access Token')->accessToken;

        return ApiResponse::sendResponse(200, 'تم انشاء الحساب بنجاح', [
            'token' => $token,
            'user'  => [
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
            ],
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Generate a reset code (6-character random string)
        $resetCode = Str::random(6);

        // Store the reset code in the database (ensure you have a reset_code column in the users table)
        $user->reset_code = $resetCode;
        $user->save();

        // Send the reset code to the user's email
        Mail::to($user->email)->send(new ResetPasswordMail($resetCode, $user->email));

        return response()->json(['message' => 'Reset code sent.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'   => 'required|string|min:6|confirmed',
            'reset_code' => 'required'
        ]);

        // Check if the user exists and the reset code matches
        $user = User::where('email', $request->email)->where('reset_code', $request->reset_code)->first();

        // Return an error if the user or reset code is invalid
        if (!$user) {
            return response()->json(['message' => __('passwords.token')], 400);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->reset_code = null; // Clear the reset code after use
        $user->save();

        return response()->json(['message' => __('passwords.reset')], 200);
    }

    public function logout(Request $request)
    {
        // Revoke the authenticated user's token
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
