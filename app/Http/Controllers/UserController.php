<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

// Models yang diperlukan untuk getDashboardData
use App\Models\University;
use App\Models\Article;

// Firebase (jika menggunakan kreait/laravel-firebase)
// use Kreait\Firebase\Auth as FirebaseAuth;
// use Kreait\Firebase\Exception\Auth\RevokedIdToken;

class UserController extends Controller
{
    // Jika menggunakan kreait/laravel-firebase, uncomment bagian ini
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
            $decodedToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $idTokenString)[1]))));
            $firebaseUid = $decodedToken->user_id ?? $decodedToken->sub ?? null;
            $email = $decodedToken->email ?? null;
            if (!$firebaseUid || !$email) {
                 return response()->json(['message' => 'Token tidak valid (simulasi).'], 401);
            }
        } catch (Exception $e) {
            Log::error('Firebase Token Verification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Token Firebase tidak valid atau kedaluwarsa.'], 401);
        }

        try {
            // FIX: Logika yang lebih aman untuk menangani Sign-Up dan Sign-In

            // Coba cari user yang sudah ada terlebih dahulu
            $user = User::where('firebase_uid', $firebaseUid)->first();

            if ($user) {
                // KASUS SIGN-IN: User sudah ada di database.
                // Tidak perlu melakukan apa-apa, cukup kembalikan data user yang ada.
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
            // Ambil data pengguna dengan relasi universitas, diurutkan dari yang terbaru
            $users = User::with('university')->latest()->get();

            // Hitung statistik dari masing-masing model
            $stats = [
                'total_users' => User::count(),
                'total_universities' => University::count(),
                'total_articles' => Article::count(),
            ];

            return response()->json([
                'users' => $users,
                'stats' => $stats,
            ]);
        } catch (Exception $e) {
            Log::error('Get Dashboard Data Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengambil data dashboard.'], 500);
        }
    }
}
