<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Pastikan Log diimpor
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;

class BudgetController extends Controller
{
    /**
     * Helper function untuk memverifikasi token dan mendapatkan user.
     */
    private function getUserByToken(Request $request)
    {
        $idTokenString = $request->bearerToken();
        if (!$idTokenString) {
            throw new Exception('Firebase token tidak disediakan.', 401);
        }

        try {
            // Ini memerlukan package 'kreait/laravel-firebase' untuk berfungsi.
            // Pastikan Anda sudah menginstalnya di server.
            $firebaseAuth = app('firebase.auth'); 
            $verifiedIdToken = $firebaseAuth->verifyIdToken($idTokenString);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');

            return User::where('firebase_uid', $firebaseUid)->first();

        } catch (Exception $e) {
            // Ini akan menangkap error jika FIREBASE_CREDENTIALS salah atau token tidak valid.
            Log::error('Token Verification Failed in BudgetController: ' . $e->getMessage());
            throw new Exception('Verifikasi token di server gagal. Periksa file .env dan log di server.', 401);
        }
    }
    
    public function show(Request $request)
    {
        try {
            $user = $this->getUserByToken($request);
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan di database.'], 404);
            }

            if (is_null($user->monthly_pocket_money)) {
                return response()->json(['message' => 'Budget plan not found for this user.'], 404);
            }
            
            $user->load('budgetItems'); 
            return response()->json($user);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 401);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $user = $this->getUserByToken($request);
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan di database.'], 404);
            }

            $validator = Validator::make($request->all(), [
                'total_pocket_money' => 'required|numeric|min:0',
                'city_of_origin' => 'required|string|max:100',
                'items' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validatedData = $validator->validated();

            DB::beginTransaction();
            $user->monthly_pocket_money = $validatedData['total_pocket_money'];
            $user->city_of_origin = $validatedData['city_of_origin'];
            $user->save();

            $user->budgetItems()->delete(); 
            if (!empty($validatedData['items'])) {
                $user->budgetItems()->createMany($validatedData['items']);
            }
            DB::commit();

            $user->load('budgetItems');
            return response()->json($user, 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan budget.', 'error' => $e->getMessage()], 500);
        }
    }
}
