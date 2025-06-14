<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerPasswordController extends Controller
{
    public function edit()
{
    
    return view('customer.my-account.changed-password.edit');
}

public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'current_password' => 'required|string',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'The current password is incorrect.']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('customer.password.edit')->with('success', 'Password updated successfully.');
}
}
