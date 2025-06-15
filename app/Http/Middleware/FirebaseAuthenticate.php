<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\RevokedIdToken;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use InvalidArgumentException;

class FirebaseAuthenticate
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function handle(Request $request, Closure $next)
    {
        $idTokenString = $request->bearerToken();

        if (!$idTokenString) {
            return response()->json(['message' => 'Firebase token not provided.'], 401);
        }

        try {
            // Verifikasi token Firebase
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idTokenString);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');

            // Cari atau buat user lokal berdasarkan UID
            $user = User::firstOrCreate(
                ['firebase_uid' => $firebaseUid],
                [
                    'name' => 'User ' . $firebaseUid,
                    'email' => $firebaseUid . '@example.com',
                ]
            );

            // Login ke sistem Laravel
            Auth::login($user);

            return $next($request);

        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'Token format is invalid.', 'error' => $e->getMessage()], 401);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['message' => 'Token is invalid or expired.', 'error' => $e->getMessage()], 401);
        } catch (RevokedIdToken $e) {
            return response()->json(['message' => 'Token has been revoked.', 'error' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'Could not process token.', 'error' => $e->getMessage()], 500);
        }
    }
}
