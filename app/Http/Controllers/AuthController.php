<?php

namespace App\Http\Controllers;

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
        ]);

        // Attempt to log the user in
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Generate an access token for the user
        $token = $user->createToken('Access Token')->accessToken;

        // Return the token and user details
        return response()->json([
            'token' => $token,
            'user'  => $user,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'gender'       => 'required|string',
            'birth_date'   => 'required|date',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        $birthDate = Carbon::parse($request->birth_date);
        $age = $birthDate->diffInYears(Carbon::now());

        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'gender'       => $request->gender,
            'birth_date'   => $request->birth_date,
            'age'          => $age,
            'password'     => Hash::make($request->password),
        ]);

        $token = $user->createToken('Access Token')->accessToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ], 201);
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
