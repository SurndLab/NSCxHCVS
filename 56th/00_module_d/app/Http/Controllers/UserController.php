<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // GET /api/users (admin)
    public function index(Request $request): JsonResponse
    {
        // TODO: cursor pagination
        return response()->json(['success' => true, 'data' => [], 'meta' => ['next_cursor' => null, 'prev_cursor' => null]]);
    }

    // PUT /api/users/{user} (admin)
    public function updateRole(Request $request, int $user): JsonResponse
    {
        // TODO:
        // - last admin demotion forbidden → 403
        // - banned user → 409 Banned user update failed
        // - invalid role → 400 Validation failed
        return response()->json(['success' => true, 'data' => null]);
    }

    // PUT /api/users/{user}/ban (admin)
    public function ban(Request $request, int $user): JsonResponse
    {
        // TODO:
        // - cannot ban self → 400 Cannot ban self
        // - cannot ban another admin → 403 Cannot ban another admin
        return response()->json(['success' => true, 'data' => null]);
    }

    // PUT /api/users/{user}/unban (admin)
    public function unban(Request $request, int $user): JsonResponse
    {
        // TODO
        return response()->json(['success' => true, 'data' => null]);
    }
}
