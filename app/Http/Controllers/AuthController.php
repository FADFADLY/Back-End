<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Laravel\Passport\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use HasApiTokens;

    public function login(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'Login Validation Errors', $validator->errors());
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $tokenResult = $user->createToken('Access Token');
            $token = $tokenResult->accessToken;
            $data['name'] =  $user->name;
            $data['email'] =  $user->email;
            return ApiResponse::sendResponse(200, 'Login Successfully', ['token' => $token, 'user' => $data]);
        } else {
            return ApiResponse::sendResponse(401, 'These credentials doesn\'t exist', null);
        }
    }

    public function register(RegisterRequest $request)
    {
       $validatedData =  $request->validated();

        $user = User::create([
            'name'         =>  $validatedData['name'],
            'username'     =>  $validatedData['username'],
            'email'        =>  $validatedData['email'],
            'gender'       =>  $validatedData['gender'],
            'age'          =>  $validatedData['age'],
            'password'     => Hash::make( $validatedData['password'])
        ]);

        $token = $user->createToken('Access Token')->accessToken;

        $data['name'] = $user->name;
        $data['username'] = $user->username;
        $data['email'] = $user->email;
        $data['gender'] = $user->gender;
        $data['age'] = $user->age;

        return ApiResponse::sendResponse(201, 'User Account Created Successfully',
            ['token' => $token, 'user' => $data]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $resetCode = Str::random(6);

        $user->reset_code = $resetCode;
        $user->save();

        Mail::to($user->email)->send(new ResetPasswordMail($resetCode, $user->email));

        return response()->json(['message' => 'Reset code sent.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'   => 'required|string|min:6|confirmed', // Ensure password confirmation
            'reset_code' => 'required' // Validate the reset code
        ]);

        $user = User::where('email', $request->email)->where('reset_code', $request->reset_code)->first();

        if (!$user) {
            return response()->json(['message' => __('passwords.token')], 400);
        }

        $user->password = Hash::make($request->password);
        $user->reset_code = null; // Clear the reset code after use
        $user->save();

        return response()->json(['message' => __('passwords.reset')], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
