<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Open login page
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    // Open register page
    public function showRegisterForm()
    {
        if (auth()->check()) {
            return redirect('/');
        }

        return view('auth.register');
    }

    // Process registration request
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $generate_token = Str::random(100);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'remember_token' => $generate_token,
        ]);

        $token = JWTAuth::fromUser($user);

        Session::put('token', $token);
        Session::put('user_token', $generate_token);

        return redirect('/login')->with('success', 'Register successfully. Please login');
    }

    // Process login request
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            Session::put('token', $token);
            Session::put('user_token', $user->remember_token);
            return redirect('/')->with('success', 'Logged in successfully.');
        }


        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Log the user out
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
