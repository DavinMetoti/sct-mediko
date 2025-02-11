<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Mail\OtpMail;
use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password');
    }

    public function showVerifyOtpPage()
    {
        return view('auth.verify-otp');
    }

    public function changePasswordPage()
    {
        return view('auth.change-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->input('email');

        $otp = OTP::generateOtp($email);

        try {
            Mail::to($email)->send(new OtpMail($otp));
            session(['otp_email' => $email]);
            return response()->json(['message' => 'OTP berhasil dikirim!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengirim OTP', 'details' => $e->getMessage()], 500);
        }
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $otpRecord = Otp::where('email', $request->email)
                        ->where('otp', $request->otp)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$otpRecord) {
            return response()->json(['error' => 'OTP expired or invalid'], 400);
        }

        $otpRecord->delete();

        return response()->json(['message' => 'OTP verified successfully']);
    }


    public function updatePassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:users,email',
                'new_password' => 'required|string|min:8',
                'confirm_password' => 'required|string|min:8|same:new_password',
            ]);

            $user = User::where('email', $validatedData['email'])->firstOrFail();

            $user->update([
                'password' => Hash::make($validatedData['new_password']),
            ]);

            session()->forget('otp_email');

            return response()->json([
                'message' => 'Password berhasil diganti.',
                'success' => true
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
