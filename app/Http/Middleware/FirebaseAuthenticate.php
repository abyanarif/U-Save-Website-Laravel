<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan model User Anda ada
// Jika Anda menggunakan kreait/firebase-php, Anda perlu mengimpornya:
// use Kreait\Firebase\Auth as FirebaseAuth;
// use Kreait\Firebase\Exception\Auth\RevokedIdToken;
// use Kreait\Firebase\Exception\InvalidToken;

class FirebaseAuthenticate
{
    // Jika menggunakan kreait/firebase-php, inject FirebaseAuth
    // protected $firebaseAuth;
    // public function __construct(FirebaseAuth $firebaseAuth)
    // {
    //     $this->firebaseAuth = $firebaseAuth;
    // }

    public function handle(Request $request, Closure $next)
    {
        $idTokenString = $request->bearerToken(); // Mengambil token dari header "Authorization: Bearer <token>"

        if (!$idTokenString) {
            return response()->json(['message' => 'Firebase token not provided.'], 401);
        }

        try {
            // --- BAGIAN VERIFIKASI TOKEN FIREBASE ---
            // Ini adalah bagian yang paling penting dan perlu disesuaikan
            // dengan library Firebase Admin SDK atau kreait/firebase-php yang Anda gunakan.

            // Contoh konseptual jika menggunakan kreait/firebase-php (Anda perlu setup service provider untuk FirebaseAuth)
            /*
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idTokenString);
            $firebaseUid = $verifiedIdToken->claims()->get('sub'); // Mendapatkan Firebase UID
            */

            // Placeholder: Ganti ini dengan logika verifikasi token yang sebenarnya
            // Untuk sekarang, kita anggap token valid jika ada dan UID bisa diekstrak (INI TIDAK AMAN, HANYA UNTUK STRUKTUR)
            // Anda HARUS mengimplementasikan verifikasi token yang sebenarnya.
            // Misalnya, Anda bisa mem-decode token jika Anda tahu cara kerjanya,
            // tapi cara terbaik adalah menggunakan Firebase Admin SDK atau library yang sesuai.
            
            // Simulasi mendapatkan UID dari token (HARUS DIGANTI DENGAN VERIFIKASI ASLI)
            $firebaseUid = $this->simulasiverifikasiTokenDanDapatkanUid($idTokenString); 
            if (!$firebaseUid) {
               return response()->json(['message' => 'Invalid or expired Firebase token (simulated).'], 401);
            }


            // Cari atau buat user di database lokal Anda
            $user = User::firstOrCreate(
                ['firebase_uid' => $firebaseUid],
                [
                    // Data default jika user baru dibuat (misalnya dari klaim token jika ada)
                    // 'name' => $verifiedIdToken->claims()->get('name', 'Firebase User'),
                    // 'email' => $verifiedIdToken->claims()->get('email'),
                    'name' => 'User ' . $firebaseUid, // Contoh nama default
                    'email' => $firebaseUid . '@example.com', // Contoh email default, pastikan unik
                    // 'password' => bcrypt(str_random(16)) // Jika kolom password NOT NULL
                ]
            );

            // Login user tersebut ke Laravel
            Auth::login($user);

            return $next($request);

        } catch (\InvalidArgumentException $e) { // Contoh dari kreait/firebase-php
            return response()->json(['message' => 'Firebase token format is invalid.', 'error' => $e->getMessage()], 401);
        // } catch (InvalidToken $e) { // Contoh dari kreait/firebase-php
        //     return response()->json(['message' => 'Firebase token is invalid or expired.', 'error' => $e->getMessage()], 401);
        // } catch (RevokedIdToken $e) { // Contoh dari kreait/firebase-php
        //     return response()->json(['message' => 'Firebase token has been revoked.', 'error' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            report($e); // Laporkan error ke log
            return response()->json(['message' => 'Could not process Firebase token.', 'error' => $e->getMessage()], 500);
        }
    }

    // FUNGSI SIMULASI - HAPUS DAN GANTI DENGAN VERIFIKASI TOKEN YANG SEBENARNYA
    private function simulasiverifikasiTokenDanDapatkanUid($tokenString) {
        // JANGAN GUNAKAN INI DI PRODUKSI. INI HANYA CONTOH STRUKTUR.
        // Anggap saja token string adalah UID jika tidak kosong untuk tes ini.
        if (!empty($tokenString) && strlen($tokenString) > 5) { // Cek sederhana
            // Dalam implementasi nyata, Anda akan memanggil Firebase Admin SDK atau library
            // untuk memverifikasi signature token dan mendapatkan UID.
            // Contoh: $verifiedIdToken = $firebaseAuth->verifyIdToken($tokenString);
            // return $verifiedIdToken->claims()->get('sub');
            return "simulated-" . substr($tokenString, -10); // Mengembalikan bagian dari token sebagai UID simulasi
        }
        return null;
    }
}