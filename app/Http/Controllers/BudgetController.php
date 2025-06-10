<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth; // Tidak kita gunakan langsung untuk tes ini
use Illuminate\Support\Facades\DB;   // Untuk transaksi database
use Illuminate\Support\Facades\Validator; // Untuk validasi
use App\Models\User;        // Pastikan model User Anda ada
use App\Models\BudgetPlan;  // Pastikan model BudgetPlan Anda ada
use App\Models\BudgetPlanItem; // Pastikan model BudgetPlanItem Anda ada
use App\Models\City;        // Tambahkan ini untuk mengambil data kota

class BudgetController extends Controller
{
    /**
     * Menampilkan halaman budgeting beserta opsi kota.
     */
    public function showBudgetingPage() // Anda bisa menamakannya index() atau sesuai kebutuhan routing Anda
    {
        // Ambil nama kota unik yang memiliki entri di tabel universities
        // Asumsi Anda punya relasi 'universities' di model City: public function universities() { return $this->hasMany(University::class); }
        // Jika tidak ada relasi, Anda perlu query yang lebih kompleks atau menyesuaikan.
        // Untuk contoh ini, kita kirim objek dengan id dan nama_kota.
        $citiesForDropdown = City::whereHas('universities') // Hanya kota yang punya universitas
                                  ->orderBy('nama_kota', 'asc')
                                  ->select('id', 'nama_kota') // Pilih kolom yang dibutuhkan
                                  ->distinct()
                                  ->get();

        // Alternatif jika Anda hanya ingin array nama kota (string):
        // $citiesForDropdown = City::whereHas('universities')
        //                           ->orderBy('nama_kota', 'asc')
        //                           ->pluck('nama_kota')
        //                           ->unique()
        //                           ->values();

        // Pastikan view 'budgeting' ada di resources/views/budgeting.blade.php
        return view('budgeting', ['citiesForDropdown' => $citiesForDropdown]);
    }

    /**
     * Menampilkan budget plan milik pengguna.
     * UNTUK PENGUJIAN: Menggunakan user pertama di database karena auth middleware dinonaktifkan sementara.
     */
    public function show(Request $request)
    {
        // $user = $request->user(); // INI AKAN NULL KARENA MIDDLEWARE AUTH SEMENTARA NONAKTIF

        // --- MODIFIKASI UNTUK PENGUJIAN ---
        // Ambil user pertama dari database untuk pengujian.
        // Pastikan ada setidaknya satu user di tabel 'users' Anda.
        $user = User::first();
        if (!$user) {
            // Jika tidak ada user sama sekali di database, kembalikan error yang jelas.
            // Anda bisa menjalankan seeder atau membuat user manual untuk tes.
            return response()->json(['message' => 'Tidak ada user di database untuk pengujian. Silakan buat user dummy terlebih dahulu.'], 404);
        }
        // --- AKHIR MODIFIKASI UNTUK PENGUJIAN ---

        // Logika asli Anda dimulai dari sini, menggunakan $user yang sudah didapatkan secara manual.
        // Ambil budget plan terbaru milik user, beserta item-itemnya
        $budgetPlan = BudgetPlan::with('items')
            ->where('user_id', $user->id)
            ->first(); // Atau ->latest()->first(); jika ada multiple dan ingin yang terbaru

        if (!$budgetPlan) {
            return response()->json(['message' => 'Budget plan tidak ditemukan untuk user pengujian ini (ID: ' . $user->id . ').'], 404);
        }

        return response()->json($budgetPlan);
        // Jika Anda ingin membungkusnya dalam 'data':
        // return response()->json(['data' => $budgetPlan]);
    }

    /**
     * Menyimpan atau memperbarui budget plan milik pengguna.
     * UNTUK PENGUJIAN: Menggunakan user pertama di database karena auth middleware dinonaktifkan sementara.
     */
    public function storeOrUpdate(Request $request)
    {
        // $user = $request->user(); // INI AKAN NULL KARENA MIDDLEWARE AUTH SEMENTARA NONAKTIF

        // --- MODIFIKASI UNTUK PENGUJIAN ---
        // Ambil user pertama dari database untuk pengujian.
        $user = User::first();
        if (!$user) {
            return response()->json(['message' => 'Tidak ada user di database untuk pengujian. Silakan buat user dummy terlebih dahulu.'], 404);
        }
        // --- AKHIR MODIFIKASI UNTUK PENGUJIAN ---

        // Validasi input dari frontend
        $validator = Validator::make($request->all(), [
            'total_pocket_money' => 'required|numeric|min:0',
            'city_of_origin' => 'nullable|string|max:100', // Sesuaikan jika Anda mengirim ID kota
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

        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Update atau buat BudgetPlan menggunakan user_id dari user pengujian
            $budgetPlan = BudgetPlan::updateOrCreate(
                ['user_id' => $user->id], // Kriteria pencarian (satu budget plan per user)
                [ // Data untuk diupdate atau dibuat
                    'total_pocket_money' => $validatedData['total_pocket_money'],
                    'city_of_origin' => $validatedData['city_of_origin'],
                    // 'plan_name' bisa ditambahkan jika ada, atau default di model/database
                ]
            );

            // Hapus item budget plan yang lama sebelum menambahkan yang baru
            $budgetPlan->items()->delete();

            // Tambahkan item-item budget plan yang baru
            $planItems = [];
            if (!empty($validatedData['items'])) { // Pastikan items tidak kosong sebelum looping
                foreach ($validatedData['items'] as $itemData) {
                    $planItems[] = new BudgetPlanItem([
                        'category_name' => $itemData['category_name'],
                        'allocated_amount' => $itemData['allocated_amount'],
                        'is_custom' => $itemData['is_custom'],
                        'original_input_amount' => $itemData['original_input_amount'] ?? null,
                    ]);
                }
                if (count($planItems) > 0) { // Hanya simpan jika ada item
                    $budgetPlan->items()->saveMany($planItems);
                }
            }

            DB::commit(); // Jika semua berhasil, commit transaksi

            // Muat ulang plan dengan items untuk respons
            $budgetPlan->load('items');

            return response()->json($budgetPlan, 200);
            // Atau jika Anda ingin membungkusnya dalam 'data':
            // return response()->json(['data' => $budgetPlan], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Jika terjadi error, rollback transaksi
            report($e); // Laporkan error (opsional, tergantung konfigurasi logging)
            return response()->json(['message' => 'Gagal menyimpan budget plan.', 'error' => $e->getMessage()], 500);
        }
    }
}
