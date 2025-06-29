<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

// Models yang diperlukan
use App\Models\User;
use App\Models\University;
use App\Models\Article;

// Firebase (jika menggunakan kreait/laravel-firebase)
// use Kreait\Firebase\Auth as FirebaseAuth;
// use Kreait\Firebase\Exception\Auth\RevokedIdToken;

class UserController extends Controller
{
    // Jika Anda sudah menginstal kreait/laravel-firebase, uncomment bagian constructor ini
    /*
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }
    */

    /**
     * Menerima token dari frontend, memverifikasinya, dan menyinkronkan
     * pengguna ke database MySQL lokal.
     */
    public function syncUser(Request $request)
    {
        $idTokenString = $request->bearerToken();
        if (!$idTokenString) {
            return response()->json(['message' => 'Firebase token tidak disediakan.'], 401);
        }

        try {
            // GANTIKAN DENGAN VERIFIKASI ASLI SETELAH PACKAGE TERINSTAL
            // Bagian simulasi ini hanya untuk tes dan mungkin tidak aman.
            $tokenParts = explode('.', $idTokenString);
            if (count($tokenParts) !== 3) {
                return response()->json(['message' => 'Struktur token tidak valid.'], 401);
            }
            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1])));
            
            $firebaseUid = $payload->user_id ?? $payload->sub ?? null;
            $email = $payload->email ?? null;
            
            if (!$firebaseUid || !$email) {
                return response()->json(['message' => 'Token tidak mengandung UID atau Email.'], 401);
            }
        } catch (Exception $e) {
            Log::error('Firebase Token Verification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Token Firebase tidak valid atau kedaluwarsa.'], 401);
        }

        try {
            // FIX: Logika yang lebih aman untuk menangani Sign-Up dan Sign-In

            // Coba cari user yang sudah ada berdasarkan firebase_uid ATAU email
            $user = User::where('firebase_uid', $firebaseUid)->orWhere('email', $email)->first();

            if ($user) {
                // KASUS SIGN-IN: User sudah ada di database.
                // Pastikan data firebase_uid ter-update jika sebelumnya null.
                if (is_null($user->firebase_uid)) {
                    $user->firebase_uid = $firebaseUid;
                    $user->save();
                }
                Log::info("User found, sync successful for UID: {$firebaseUid}");

            } else {
                // KASUS SIGN-UP: User tidak ditemukan, buat entri baru.
                Log::info("User not found, creating new user for UID: {$firebaseUid}");

                // Ambil data tambahan dari request
                $username = $request->input('username');
                $university_id = $request->input('university_id');
                $phone = $request->input('phone');
                $role = $request->input('role', 'user'); // Default role 'user'

                // Validasi data khusus untuk user baru
                $validator = Validator::make($request->all(), [
                    'username' => 'required|string|unique:users,username',
                    'university_id' => 'nullable|exists:universities,id',
                    'phone' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
                }

                // Buat user baru di database
                $user = User::create([
                    'firebase_uid'  => $firebaseUid,
                    'email'         => $email,
                    'username'      => $username,
                    'university_id' => $university_id,
                    'phone'         => $phone,
                    'role'          => $role,
                ]);
            }

            return response()->json([
                'message' => 'User berhasil disinkronkan.',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            Log::error('User Sync Database Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan data pengguna ke database.'], 500);
        }
    }

    /**
     * Mengambil data agregat untuk ditampilkan di dashboard admin.
     */
    public function getDashboardData()
    {
        try {
            $users = User::with('university')->latest()->get();
            $stats = [
                'total_users' => User::count(),
                'total_universities' => University::count(),
                'total_articles' => Article::count(),
            ];

            return response()->json(['users' => $users, 'stats' => $stats]);
        } catch (Exception $e) {
            Log::error('Get Dashboard Data Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengambil data dashboard.'], 500);
        }
    }
    
    /**
     * Mengambil data profil untuk admin yang sedang login.
     */
    public function getAdminProfile(Request $request)
    {
        $admin = Auth::user() ?? User::where('role', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'Admin tidak ditemukan atau tidak login.'], 404);
        }
        
        return response()->json($admin);
    }

    /**
     * Mengambil data profil untuk pengguna biasa.
     */
    public function getUserProfile(Request $request)
    {
        $firebaseUid = $request->query('firebase_uid');
        if (!$firebaseUid) { return response()->json(['error' => 'Parameter firebase_uid dibutuhkan'], 400); }

        $user = User::where('firebase_uid', $firebaseUid)->with('university')->first();
        if (!$user) { return response()->json(['error' => 'User tidak ditemukan'], 404); }

        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'university_id' => $user->university_id,
            'university' => $user->university->name ?? null,
            'phone' => $user->phone ?? null,
        ]);
    }

    /**
     * Memperbarui data profil untuk pengguna biasa.
     */
    public function updateUserProfile(Request $request)
    {
        $idTokenString = $request->bearerToken();
        if (!$idTokenString) { return response()->json(['message' => 'Token tidak disediakan.'], 401); }
        
        try {
            $decodedToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $idTokenString)[1]))));
            $firebaseUid = $decodedToken->user_id ?? $decodedToken->sub ?? null;
            if (!$firebaseUid) { return response()->json(['message' => 'Token tidak valid.'], 401); }
        } catch (Exception $e) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $user = User::where('firebase_uid', $firebaseUid)->first();
        if (!$user) { return response()->json(['message' => 'User tidak ditemukan.'], 404); }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username,' . $user->id,
            'university_id' => 'required|exists:universities,id',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        $user->update($validator->validated());

        return response()->json(['message' => 'Profil berhasil diperbarui!', 'user' => $user]);
    }
}
