<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        $isAdmin = ($user->role ?? null) === 'admin' || (bool) ($user->is_admin ?? false);

        if (! $isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access required.',
            ], 403);
        }

        // Revoke previous tokens for this admin (simple & safer default).
        $user->tokens()->delete();

        $token = $user->createToken('admin')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? null,
                'is_admin' => (bool) ($user->is_admin ?? false),
            ],
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()?->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out.',
            'data' => null,
        ], 200);
    }
}

