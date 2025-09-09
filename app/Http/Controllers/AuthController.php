<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'school_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('school_id', $request->school_id)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'         => $user->id,
                'full_name'  => $user->full_name,
                'school_id'  => $user->school_id,
                'role'       => $user->role,
                'email'      => $user->email,
                'photo'      => $user->photo,
            ],
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
