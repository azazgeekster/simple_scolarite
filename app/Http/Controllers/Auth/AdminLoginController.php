<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller {
    public function showLoginForm() {
        return view('auth.admin-login');
    }

    public function login(AdminLoginRequest $request) {
        $credentials = $request->only('email', 'password');  // Extract validated credentials

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();  // Regenerate the session to prevent fixation attacks
            return redirect()->route('admin.dashboard');
            // return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);    
    }


    public function logout(){
        auth('admin')->logout();
        return redirect()->route('admin.loginform');  
      }
}
