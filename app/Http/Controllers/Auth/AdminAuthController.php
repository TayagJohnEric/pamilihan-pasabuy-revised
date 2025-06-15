<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $credentials['role'] = 'admin';

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    if ($request->ajax()) {
        return response()->json([
            'errors' => ['email' => ['Invalid credentials or not a customer.']]
        ], 422);
    }

    return back()->withErrors([
        'email' => 'Invalid credentials or not a customer.',
    ]);
}

public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
