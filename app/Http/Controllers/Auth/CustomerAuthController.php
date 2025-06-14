<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.customer.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $credentials['role'] = 'customer';

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        return redirect()->intended(route('customer.dashboard'));
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

    public function showRegisterForm()
    {
        return view('auth.customer.register');
    }

   public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|email|unique:users',
        'password'   => 'required|confirmed|min:8',
    ]);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'customer',
    ]);

    Auth::login($user);

    if ($request->expectsJson()) {
        return response()->json(['redirect' => route('customer.dashboard')]);
    }

    return redirect()->route('customer.dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
