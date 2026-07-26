<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerifyFirebaseToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');

        if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized: Missing or invalid Authorization header.'], 401);
        }

        $token = substr($authorization, 7);

        // For local development and ease of integration, we decode the Firebase JWT payload.
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            // Check if they passed a raw UID directly (useful for testing or simpler debugging/fallback)
            if (strlen($token) >= 20 && !str_contains($token, ' ')) {
                $firebaseUid = $token;
                $email = $token . '@rankit.demo';
                $name = 'Firebase User';
            } else {
                return response()->json(['message' => 'Unauthorized: Invalid token format.'], 401);
            }
        } else {
            // Base64URL decode the JWT payload
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            if (!$payload) {
                return response()->json(['message' => 'Unauthorized: Failed to decode token payload.'], 401);
            }

            $firebaseUid = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            // Extract display name or fallback
            $name = $payload['name'] ?? ($email ? explode('@', $email)[0] : 'Firebase User');
        }

        if (!$firebaseUid) {
            return response()->json(['message' => 'Unauthorized: Firebase User ID (sub) not found in token.'], 401);
        }

        // Find or dynamically register the user in our local database
        $user = User::find($firebaseUid);

        if (!$user) {
            // Automatically sync the user in our local DB
            $user = User::create([
                'id' => $firebaseUid,
                'name' => $name,
                'email' => $email ?? ($firebaseUid . '@firebase.user'),
                'password' => null, // No local password
            ]);
        }

        // Authenticate the user for the current request
        Auth::login($user);

        return $next($request);
    }
}
