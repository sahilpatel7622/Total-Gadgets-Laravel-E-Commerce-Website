<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    // Forgot Password Page
    public function forgotPassword()
    {
         $email = Auth::check() ? Auth::user()->email : '';
        return view('forget-password.index', compact('email'));
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
        Otp::where('email', $user->email)
            ->where('type', 'forgot_password')
            ->delete();

        Otp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $otp,  
            'type' => 'forgot_password',
            'expiry' => now()->addMinutes(5),
        ]);

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

        $otpData = Otp::where('email', session('reset_email'))
            ->where('otp', $request->otp)
            ->where('type', 'forgot_password')
            ->latest()
            ->first();

        if (!$otpData) {
            return back()->with('error', 'Invalid OTP.');
        }

        if (now()->gt($otpData->expiry)) {
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
                'min:6',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be minimum 6 characters.',
            'password.confirmed' => 'Confirm password does not match.',
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
        Otp::where('email', $user->email)
            ->where('type', 'forgot_password')
            ->delete();
        $user->save();

        Mail::html("
        <div style='font-family:Arial,sans-serif; max-width:600px; margin:auto; border:1px solid #ddd;'>
            <div style='background:#4F46E5; color:white; padding:15px; text-align:center;'>
                <h2>Total Gadgets</h2>
            </div>
            <div style='padding:20px;'>
                <p>Hi <strong>{$user->name}</strong>,</p>
                <p>
                    Your account password has been changed successfully.
                </p>
                <p>
                    If this was you, no further action is required.
                </p>
                <p>
                    Regards,<br>
                    <strong>Total Gadgets Team</strong>
                </p>
            </div>
            <div style='background:#f1f1f1; text-align:center; padding:10px; font-size:13px; color:#666;'>
                © ".date('Y')." Total Gadgets. All Rights Reserved.
            </div>
        </div>
        ", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Password Changed Successfully');
        });

        session()->forget('reset_email');
        if (Auth::check()) {
            return redirect()
                ->route('profile.security')
                ->with('success', 'Password updated successfully!.');
        }

        return redirect()
            ->route('login')
            ->with('success', 'Password updated successfully!. Please login.');

    }

}