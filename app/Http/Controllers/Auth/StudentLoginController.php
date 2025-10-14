<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentLoginRequest;
use App\Mail\ActivationEmail;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function login(StudentLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');  // Extract validated credentials
        $student = Student::where('email', $request->email)->first();
        if ($student && !$student->is_active) {
            auth()->guard('student')->logout();

            return back()->with(['error' => 'Please activate your account before logging in, by clicking "I don\'t have an account.']);
        }
        if (Auth::guard('student')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();  // Regenerate the session to prevent fixation attacks
            return redirect()->route('student.dashboard');
            // return redirect()->intended(route('student.dashboard'));
        }
        /// i added this

        return redirect()->back()->with('error', 'Email or password wrong.');

    }

    public function logout(Request $r)
{
    $student = auth('student')->user();

    if ($student) {
        $student->update(['last_login' => now()]); // Update last login time
    }

    auth('student')->logout();
    $r->session()->invalidate();

    // return redirect()->route('student.loginform');
    return redirect()->route('student.logoutpage');
}



}
