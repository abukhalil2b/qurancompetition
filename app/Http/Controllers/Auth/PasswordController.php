<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RequestPhoneOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Logged;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function forgotPasswordForm()
    {
        return view('auth.forgot_password_form');
    }

    public function createAccountForm()
    {
        return view('auth.create_account_form');
    }

   
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'civil_id' => ['required', 'string'],
        ]);

        $phone = $request->input('phone');

        $civil_id = $request->input('civil_id');

        // Throttle based on phone
        $key = 'otp-request:' . $phone;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            // Get the number of seconds remaining until the key is available again
            $seconds = RateLimiter::availableIn($key);

            // Convert seconds to a more readable format (e.g., minutes and seconds)
            $minutes = ceil($seconds / 60);

            return back()->withErrors(['phone' => "لقد قمت بمحاولات كثيرة. حاول بعد {$minutes} دقائق."]);
        }

        // Ensure phone belongs to a registered trainee
        $user = User::where(['phone' => $phone, 'civil_id' => $civil_id])->first();

        if (!$user) {
            RateLimiter::hit($key, 300);
            return back()->withErrors(['phone' => 'رقم الهاتف غير موجود في النظام.']);
        }

        // Generate and store OTP
        $otp = rand(10000, 99999);
        $requestPhoneOtp = RequestPhoneOtp::create([
            'phone' => $phone,
            'otp' => $otp,
            'purpose' => 'password_reset',
            'expires_at' => now()->addMinutes(5),
            'ip' => request()->ip(),
            'status' => 'pending', // default
        ]);

        // Send the OTP via SMS
        $sent = sendSMS($phone, "منصة مساري للتدريب\nرمز التحقق: $otp");

        if (!$sent) {
            // Update status to failed before returning
            $requestPhoneOtp->update(['status' => 'failed']);

            return back()->withErrors(['phone' => 'فشل في إرسال رمز التحقق.']);
        }

        // Update status to sent if successful
        $requestPhoneOtp->update(['status' => 'sent']);

        RateLimiter::hit($key, 300);

        return redirect()->route('verify_otp_form')
            ->with('phone', $phone)
            ->with('status', 'تم إرسال رمز التحقق بنجاح.');
    }

    public function verifyOtpForm(Request $request)
    {
        $phone = $request->session()->get('phone');
        return view('auth.received_phone_otp')->with('phone', $phone);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'digits:5'],
        ]);

        $phone = $request->input('phone');
        $otp = $request->input('otp');

        // Apply rate limiting to prevent brute-force attacks on the OTP
        $key = 'otp-verify:' . $phone;
        $maxAttempts = 5; // Allow 5 attempts to verify the OTP
        $decayMinutes = 10; // Lock the user out for 10 minutes after 5 failed attempts

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            return back()->withErrors(['otp' => "لقد قمت بمحاولات كثيرة. حاول بعد {$minutes} دقائق."]);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            // Use a generic error message for security
            RateLimiter::hit($key, $decayMinutes * 60);
            return back()->withErrors(['phone' => "رقم الهاتف أو رمز التحقق غير صحيح."]);
        }

        $otpRecord = RequestPhoneOtp::where('phone', $phone)
            ->where('status', 'sent')
            ->orderByDesc('created_at')
            ->first();

        if (!$otpRecord || $otpRecord->created_at->addMinutes(10)->isPast()) {
            RateLimiter::hit($key, $decayMinutes * 60);
            return back()->withErrors(['otp' => 'فشل في التحقق.']);
        }

        if ($otpRecord->otp !== $otp) {
            // If the OTP is incorrect, increment the rate limiter counter
            RateLimiter::hit($key, $decayMinutes * 60);
            return back()->withErrors(['otp' => 'رمز التحقق غير صحيح.']);
        }

        // If the OTP is correct, mark it as used and clear the rate limiter
        $otpRecord->update(['status' => 'verified']);
        RateLimiter::clear($key);

        // Proceed to send the temporary password
        return $this->sendTempPassword($user);
    }

    public function sendTempPassword($user)
    {
        // Generate temporary password
        $tempPassword = Str::random(6);

        $user->password = Hash::make($tempPassword);
        $user->plain_password = $tempPassword;
        $user->force_password_reset = true;
        $user->save();

        // Send SMS and capture result
        $sent = sendSMS($user->phone, "منصة مساري للتدريب\nكلمة مرور : $tempPassword");

        if (!$sent) {
            return back()->withErrors(['phone' => 'فشل في إرسال كلمة المرور. حاول لاحقًا.']);
        }

        return redirect()->route('login')->with('status', "لقد قمنا بإرسال كلمة مرور.");
    }
}
