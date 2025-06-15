<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\BudgetPlan;
use App\Models\BudgetPlanItem;

class BudgetController extends Controller
{
    /**
     * Menampilkan budget plan yang tersimpan milik pengguna yang sedang login.
     */
    public function show(Request $request)
    {
        // Untuk pengujian, kita gunakan user pertama jika tidak ada yang login.
        // Nanti ini akan diganti dengan middleware autentikasi yang sebenarnya.
        $user = Auth::user() ?? User::first(); 
        
        if (!$user) {
            return response()->json(['message' => 'Tidak ada user untuk dites.'], 404);
        }

        $budgetPlan = BudgetPlan::with('items')->where('user_id', $user->id)->first();

        if (!$budgetPlan) {
            return response()->json(['message' => 'Budget plan not found for this user.'], 404);
        }
        return response()->json($budgetPlan);
    }

    /**
     * Menyimpan atau memperbarui budget plan milik pengguna.
     */
    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user() ?? User::first();

        if (!$user) {
            return response()->json(['message' => 'Tidak ada user untuk dites.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'total_pocket_money' => 'required|numeric|min:0',
            'city_of_origin' => 'required|string|max:100',
            'items' => 'required|array',
            'items.*.category_name' => 'required|string|max:100',
            'items.*.allocated_amount' => 'required|numeric|min:0',
            'items.*.is_custom' => 'required|boolean',
            'items.*.original_input_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        DB::beginTransaction();
        try {
            $budgetPlan = BudgetPlan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'total_pocket_money' => $validatedData['total_pocket_money'],
                    'city_of_origin' => $validatedData['city_of_origin'],
                ]
            );

            $budgetPlan->items()->delete(); // Hapus item lama
            $budgetPlan->items()->createMany($validatedData['items']); // Buat item baru

            DB::commit();

            $budgetPlan->load('items'); // Muat ulang relasi items untuk respons
            return response()->json($budgetPlan, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan budget plan.', 'error' => $e->getMessage()], 500);
        }
    }
}
