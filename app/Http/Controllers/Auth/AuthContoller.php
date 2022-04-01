<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthContoller extends Controller
{
    // registration
    public function register(RegisterRequest $request)
    {
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'role_id' => 2,
            'password' => Hash::make($request->input('password'))
        ]);

        return response(['message' => 'Registration successful'], Response::HTTP_ACCEPTED);
    }

    // login
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('user')->accessToken;

            $cookie = cookie('jwt', $token, 7200);

            return response(['token' => $token], Response::HTTP_ACCEPTED)->withCookie($cookie);
        }

        return response(["error" => "Email/Password Invalid"], Response::HTTP_BAD_REQUEST);
    }

    //logout
    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message' => 'Logout successful'], Response::HTTP_OK)->withCookie($cookie);
    }
}
