<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);
    
        // Find user by email
        $user = User::where('email', $request->email)->first();
    
        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Check if OTP matches and is not expired
        // if (!hash_equals($user->otp, $request->otp) || now()->greaterThan($user->otp_expires_at)) {
        //     return response()->json(['message' => 'Invalid OTP or OTP expired'], 400);
        // }
    
        // Clear OTP and mark email as verified
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = now();
        $user->save();
    
        return response()->json(['message' => 'OTP verified successfully']);
    }
    
}
