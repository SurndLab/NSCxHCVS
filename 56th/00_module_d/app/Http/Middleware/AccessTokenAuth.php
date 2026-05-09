<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('X-Authorization');

        if (!$header) {
            return response()->json([
                'success' => false,
                'message' => 'Access Token is required',
            ], 401);
        }

        $token = str_starts_with($header, 'Bearer ')
            ? substr($header, 7)
            : $header;
        $token = trim($token);

        if ($token === '') {
            return response()->json([
                'success' => false,
                'message' => 'Access Token is required',
            ], 401);
        }

        $row = AccessToken::with('user')->where('token', $token)->first();

        if (!$row || !$row->user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Access Token',
            ], 401);
        }

        if ($row->user->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'User is banned',
            ], 403);
        }

        $request->attributes->set('auth_user', $row->user);
        $request->attributes->set('auth_token', $row);

        return $next($request);
    }
}
