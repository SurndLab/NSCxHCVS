<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 400);
        }

        if ($user->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'User is banned',
            ], 403);
        }

        $token = strtolower(md5($user->username));

        AccessToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token, 'created_at' => now()]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $this->presentUser($user),
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:1',
        ]);

        if (User::where('username', $data['username'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Username already taken',
            ], 409);
        }

        if (User::where('email', $data['email'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already taken',
            ], 409);
        }

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'user',
            'is_banned' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => ['user' => $this->presentUser($user)],
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->attributes->get('auth_token');

        if ($token) {
            $token->delete();
        }

        return response()->json(['success' => true]);
    }

    private function presentUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at?->toISOString(),
            'updated_at' => $user->updated_at?->toISOString(),
        ];
    }
}
