<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountActivationController extends Controller
{
    /**
     * Show the activation request form.
     */
    public function showActivationForm()
    {
        return view('auth.activate');
    }

    /**
     * Handle activation request.
     */
    public function sendActivationEmail(Request $request)
    {
        $request->validate([
            'cne' => 'required|exists:students,cne',
            'email' => 'required|email|exists:students,email',
        ]);

        $student = Student::where('cne', $request->cne)->where('email', $request->email)->first();

        if (!$student) {
            return back()->with('error', 'No matching student found.');
        }

        if ($student->is_active) {
            return redirect()->route('student.loginform')->with('message', 'Your account is already activated.');
        }

        // Generate activation token
        $student->update([
            'activation_token' => Str::random(60),
            'email_verified_at' => now(),
        ]);


        // Send activation email
        Mail::send('emails.activate-student', ['student' => $student], function ($message) use ($student) {
            $message->to($student->email)->subject('Activate Your Account');
        });

        return back()->with('message', 'Activation link has been sent to your email.');
    }

    /**
     * Show password setup form.
     */
    public function showSetPasswordForm($token)
    {
        $student = Student::where('activation_token', $token)->first();

        if (!$student) {
            return redirect()->route('student.loginform')->with('error', 'Invalid or expired activation link.');
        }

        return view('auth.set_password', compact('token'));
    }

    /**
     * Complete account activation.
     */
    public function completeActivation(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $student = Student::where('activation_token', $token)->first();

        if (!$student) {
            return redirect()->route('student.loginform')->with('error', 'Invalid or expired activation link.');
        }

        // Activate student and set password
        $student->password = Hash::make($request->password);
        $student->is_active = true;
        // $student->activation_token = null;
        $student->save();

        return redirect()->route('student.loginform')->with('message', 'Account activated successfully. You can now log in.');
    }
}
