<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]); // Ensure this view exists
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:students,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('students')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Student $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('student.loginform')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
