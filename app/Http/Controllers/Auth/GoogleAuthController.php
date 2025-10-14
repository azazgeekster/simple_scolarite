<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback()
{
    try {
        // Get the user information from Google
        $googleUser = Socialite::driver('google')->user();
    } catch (Throwable $e) {
        return redirect('/')->with('error', 'Google authentication failed.');
    }

    // Check if the user exists in the 'students' table
    $existingStudent = Student::where('email', $googleUser->email)->first();

    if ($existingStudent) {
        // Login the student
        Auth::guard('student')->login($existingStudent);
        return redirect()->route('student.dashboard');
    }

    // If the student does not exist, ask them to register
    return redirect(route("student.loginform"))->withErrors(['error'=> 'No student account found. Please create an account first.']);
}

}
