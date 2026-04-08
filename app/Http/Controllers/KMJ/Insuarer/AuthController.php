<?php

namespace App\Http\Controllers\KMJ\Insuarer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('insuarer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('insuarer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('insuarer.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('insuarer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('insuarer.login');
    }
}
