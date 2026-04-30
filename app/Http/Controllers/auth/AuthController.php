<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\VerifyCustomerEmail;

class AuthController extends Controller
{
    // ADMIN AUTH
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

    // END ADMIN AUTH

    // CUSTOMER AUTH
    public function CustomerLoginPage()
    {
        return view('auth.customers.login');
    }

    public function CustomerLoginRequest(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $customer = Auth::guard('web')->user();

            if ($customer->is_verified != 1) {
                Auth::guard('web')->logout();
                return redirect()->back()->withInput($request->only('email'))->with('error', 'Your account is not verified yet. Please check your email.');
            }

            return redirect()->route('customers.home.page')->with('success', 'Welcome back!');
        }
        return redirect()->back()->withInput($request->only('email'))->with('error', 'Invalid credentials');
    }

    public function CustomerRegisterPage()
    {
        return view('auth.customers.register');
    }

    public function CustomerRegisterRequest(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer = Customers::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'is_verified' => 0,
        ]);

        // Generate a signed URL for verification
        $verificationUrl = URL::temporarySignedRoute(
            'customers.verify',
            now()->addMinutes(60),
            ['id' => $customer->id]
        );

        // Send Email
        Mail::to($customer->email)->send(new VerifyCustomerEmail($customer, $verificationUrl));

        return redirect()->route('customers.login.page')->with('success', 'Account created! Please check your email to verify your account.');
    }

    public function VerifyCustomer(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('customers.login.page')->with('error', 'Invalid or expired verification link.');
        }

        $customer = Customers::findOrFail($id);

        if ($customer->is_verified == 1) {
            return redirect()->route('customers.login.page')->with('success', 'Email already verified.');
        }

        $customer->update(['is_verified' => 1]);

        return redirect()->route('customers.login.page')->with('success', 'Email successfully verified!');
    }

    public function CustomerLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customers.login.page')->with('success', 'Successfully logged out');
    }

    // END CUSTOMERS AUTH
}
