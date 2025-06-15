<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use App\Models\User;

class FirebaseAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['message' => 'Token tidak ditemukan.'], 401);
        }

        $idToken = $matches[1];

        try {
            $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
            $verifiedIdToken = $factory->createAuth()->verifyIdToken($idToken);

            $uid = $verifiedIdToken->claims()->get('sub');
            $user = User::where('firebase_uid', $uid)->first();

            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan di database.'], 404);
            }

            Auth::login($user);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Token tidak valid.', 'error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
