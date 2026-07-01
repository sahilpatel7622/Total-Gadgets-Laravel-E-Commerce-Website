<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class GmailController extends Controller
{
    // Forgot Password Page
    public function forgotPassword()
    {
        return view('forget-password.index');
    }

    // Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user,email',
        ], [
            'email.exists' => 'Email not found.',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);

        $user->reset_otp = $otp;
        $user->reset_otp_expiry = now()->addMinutes(5);
        $user->save();

        Mail::html("
        <div style='max-width:600px;margin:auto;padding:30px;
                    font-family:Arial,sans-serif;
                    border:1px solid #e5e7eb;
                    border-radius:10px;
                    background:#f9fafb;'>

            <div style='text-align:center'>
                <h2 style='color:#4f46e5;margin-bottom:10px;'>
                    Total Gadgets
                </h2>

                <p style='font-size:16px;color:#555;'>
                    Password Reset OTP
                </p>

                <div style='margin:30px 0'>
                    <span style='font-size:34px;
                                 font-weight:bold;
                                 color:#111827;
                                 letter-spacing:6px;'>
                        {$otp}
                    </span>
                </div>

                <p style='color:#666'>
                    This OTP is valid for
                    <strong>5 Minutes</strong>.
                </p>

                <p style='color:#999;font-size:13px'>
                    Do not share this OTP with anyone.
                </p>
            </div>

        </div>
        ", function ($message) use ($user) {

            $message->to($user->email)
                    ->subject('Password Reset OTP');
        });

        session([
            'reset_email' => $user->email
        ]);

        return redirect()
            ->route('verify.otp')
            ->with('success', 'OTP sent successfully.');
    }

    // Verify OTP Page
    public function verifyOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('forgot.password');
        }

        return view('forget-password.verify-otp');
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) {
            return redirect()
                ->route('forgot.password')
                ->with('error', 'Session expired.');
        }

        if ($user->reset_otp != $request->otp) {
            return back()->with('error', 'Invalid OTP.');
        }

        if (now()->gt($user->reset_otp_expiry)) {
            return back()->with('error', 'OTP expired.');
        }

        session([
            'otp_verified' => true
        ]);

        return redirect()->route('reset.password')->with('success', 'OTP Verify successfully.');
    }

    // Reset Password Page
    public function resetPasswordForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('forgot.password');
        }

        return view('forget-password.reset-password');
    }

    // Update Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'digits:6',
                'confirmed',
            ],
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('forgot.password')
                ->with('error', 'Session expired. Please try again.');
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'New password cannot be the same as your old password.'
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->reset_otp = null;
        $user->reset_otp_expiry = null;
        $user->save();
        session()->forget('reset_email');
        return redirect('/login')->with('success', 'Password updated successfully.');
    }

}