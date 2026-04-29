<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('web.auth.login');
    }

    // ── Send OTP ──────────────────────────────────────
    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|digits:10']);

        $phone = '91' . $request->phone;

        // Expire old OTPs
        Otp::where('phone', $phone)->update(['is_used' => true]);

        $otp = rand(1000, 9999);

        Otp::create([
            'phone'      => $phone,
            'otp'        => (string) $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // DEV: log to check OTP
        Log::info("DocBook OTP for {$phone} : {$otp}");

        // Production: uncomment Twilio / Gupshup here
        /*
        \Http::post('https://api.gupshup.io/sm/api/v1/msg', [
            'channel'  => 'whatsapp',
            'source'   => config('services.gupshup.number'),
            'destination' => $phone,
            'message'  => "Your DocBook OTP is: {$otp}",
            'src.name' => 'DocBook',
        ]);
        */

        return response()->json(['success' => true]);
    }

    // ── Verify OTP ─────────────────────────────────────
    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|string',
    //         'otp'   => 'required|string|size:4',
    //     ]);

    //     // Normalize phone — remove leading 91 if already there
    //     $phone = '91' . ltrim($request->phone, '91');
    //     // Handle case where user types 10-digit vs full number
    //     if (strlen($phone) > 12) {
    //         $phone = substr($phone, -12); // keep last 12 digits
    //     }

    //     Log::info("Verifying OTP for phone: {$phone}, OTP: {$request->otp}");

    //     $record = Otp::where('phone', $phone)
    //                   ->where('is_used', false)
    //                   ->latest()
    //                   ->first();

    //     if (!$record) {
    //         Log::warning("No OTP record found for {$phone}");
    //         return back()->withErrors(['otp' => 'OTP expired. Please request a new one.']);
    //     }

    //     if (!$record->isValid($request->otp)) {
    //         Log::warning("Invalid OTP. Expected: {$record->otp}, Got: {$request->otp}");
    //         return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
    //     }

    //     // Mark OTP as used
    //     $record->update(['is_used' => true]);

    //     // Find or create user by phone
    //     $user = User::firstOrCreate(
    //         ['phone' => $phone],
    //         ['phone_verified_at' => now()]
    //     );

    //     // Update verified_at if not set
    //     if (!$user->phone_verified_at) {
    //         $user->update(['phone_verified_at' => now()]);
    //     }

    //     // ✅ Login using web guard
    //     Auth::guard('web')->login($user, remember: true);

    //     Log::info("User logged in: {$user->id}, phone: {$user->phone}");

    //     return redirect()->intended(route('home'))
    //            ->with('success', 'Welcome to DocBook! 🎉');
    // }

    public function verifyOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'otp'   => 'required|string|size:4',
    ]);

    // ✅ phone always 10 digits വരും (hidden field-ൽ raw 10-digit store ചെയ്തതാണ്)
    // strip everything, keep last 10 digits, then prepend 91
    $rawPhone = preg_replace('/\D/', '', $request->phone); // digits only
    $phone = '91' . substr($rawPhone, -10);               // always 91 + 10 digits = 12

    \Log::info("Verifying OTP for phone: {$phone}, OTP: {$request->otp}");

    $record = Otp::where('phone', $phone)
                  ->where('is_used', false)
                  ->latest()
                  ->first();

    if (!$record) {
        return back()->withErrors(['otp' => 'OTP expired. Please request a new one.']);
    }

    if (!$record->isValid($request->otp)) {
        return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
    }

    $record->update(['is_used' => true]);

    $user = User::firstOrCreate(
        ['phone' => $phone],
        ['phone_verified_at' => now()]
    );

    if (!$user->phone_verified_at) {
        $user->update(['phone_verified_at' => now()]);
    }

    Auth::guard('web')->login($user, remember: true);

    return redirect()->intended(route('home'))
           ->with('success', 'Welcome to DocBook! 🎉');
}

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}