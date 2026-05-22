<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function loginUser(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {

            return redirect()->route('dashboard')->with('success', 'Login successful.');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);

    }
}