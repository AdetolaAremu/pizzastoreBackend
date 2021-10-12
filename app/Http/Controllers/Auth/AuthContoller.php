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
        $user = User::create($request->only('first_name', 'last_name', 'email', 'role_id') + [
            'password' => Hash::make($request->input('password'))
        ]);

        return response(['message' => 'Registration successful'], Response::HTTP_ACCEPTED);
    }

    // login
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('admin')->accessToken;

            $cookie = cookie('jwt', $token, 7200);

            return response(['token' => $token], Response::HTTP_ACCEPTED)->withCookie($cookie);
        }

        return response(["error" => "Email/Password Invalid"]);
    }

    //logout
    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message' => 'Logout successful'], Response::HTTP_OK)->withCookie($cookie);
    }
}
