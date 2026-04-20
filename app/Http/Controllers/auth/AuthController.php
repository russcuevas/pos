<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function AdminLoginPage()
    {
        return view('auth.admin.login');
    }

    public function AdminLoginRequest(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard.page')->with('success', 'Successfully login');
        }
        return redirect()->back()->withInput($request->only('email'))->with('error', 'Invalid credentials');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.page')->with('success', 'Successfully logout');
    }
}
