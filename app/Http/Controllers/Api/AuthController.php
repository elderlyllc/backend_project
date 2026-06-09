<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = auth()->user();

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user_id' => $user->id,
            'user' => $user,
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]);
    }
   
public function updateUserDetails(Request $request, $id)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
    ]);

    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found',
        ], 404);
    }

    $user->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'date_of_birth' => $request->date_of_birth,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'User details updated successfully',
        'data' => $user,
    ]);
}

public function getProfile($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'User profile fetched successfully',
        'data' => $user,
    ]);
}
public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $otpCode = rand(100000, 999999);

    // Delete existing OTP for this email
    Otp::where('email', $request->email)->delete();

    // Create new OTP
    Otp::create([
        'email' => $request->email,
        'otp_code' => $otpCode,
        'expires_at' => now()->addMinutes(10),
    ]);

    // Send OTP via email
    Mail::raw("Your OTP is: " . $otpCode . "\n\nThis OTP will expire in 10 minutes.", function ($message) use ($request) {
        $message->to($request->email)
            ->subject('Your OTP Code');
    });

    return response()->json([
        'status' => true,
        'message' => 'OTP sent successfully to ' . $request->email,
    ]);
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp_code' => 'required|digits:6',
    ]);

    // Find OTP record
    $otp = Otp::where('email', $request->email)
        ->where('otp_code', $request->otp_code)
        ->first();

    if (!$otp) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP code',
        ], 401);
    }

    // Check if OTP has expired
    if ($otp->isExpired()) {
        $otp->delete();
        return response()->json([
            'status' => false,
            'message' => 'OTP has expired',
        ], 401);
    }

    // Delete OTP after verification
    $otp->delete();

    // Find or create user
    $user = User::firstOrCreate(
        ['email' => $request->email],
        [
            'name' => $request->email,
            'password' => Hash::make(uniqid()),
        ]
    );

    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'status' => true,
        'message' => 'OTP verified successfully',
        'token' => $token,
        'user_id' => $user->id,
        'user' => $user,
    ]);
}

}