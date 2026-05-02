<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
    ]);

    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found',
        ], 404);
    }

    $user->update([
        'first_name' => $request->first_name ?? $user->first_name,
        'last_name' => $request->last_name ?? $user->last_name,
        'date_of_birth' => $request->date_of_birth ?? $user->date_of_birth,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'User details updated successfully',
        'user' => $user,
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
}