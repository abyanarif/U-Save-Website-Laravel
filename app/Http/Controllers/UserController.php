<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function syncUser(Request $request)
{
    $firebaseUid = $request->input('firebase_uid');
    $email = $request->input('email');
    $username = $request->input('username');

    $user = User::where('firebase_uid', $firebaseUid)->first();

    if (!$user) {
        // Buat user baru jika belum ada
        $user = User::create([
            'firebase_uid' => $firebaseUid,
            'email' => $email,
            'username' => $username,
        ]);
    }

    return response()->json([
        'message' => 'User ditemukan atau dibuat',
        'user' => $user,
    ]);
}
public function getUserProfile(Request $request)
{
    $firebaseUid = $request->input('firebase_uid');

    $user = User::where('firebase_uid', $firebaseUid)->first();

    if (!$user) {
        return response()->json(['error' => 'User tidak ditemukan di backend'], 404);
    }

    return response()->json(['user' => $user]);
}


}

