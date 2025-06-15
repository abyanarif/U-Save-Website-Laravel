<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// Pastikan semua model ini sudah Anda buat
use App\Models\BudgetCategory;
use App\Models\BudgetAllocationRule;
use App\Models\UmrData;

class AdminBudgetingController extends Controller
{
    // --- KATEGORI BUDGET ---
    // (Fungsi-fungsi getCategories, addCategory, dll. tidak ada perubahan)
    public function getCategories()
    {
        return response()->json(BudgetCategory::orderBy('id', 'asc')->get());
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:budget_categories,name',
            'display_text' => 'required|string',
            'icon_class' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $category = BudgetCategory::create($validator->validated());
        return response()->json(['message' => 'Kategori berhasil ditambahkan', 'data' => $category], 201);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = BudgetCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:budget_categories,name,' . $id,
            'display_text' => 'required|string',
            'icon_class' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $category->update($validator->validated());
        return response()->json(['message' => 'Kategori berhasil diperbarui', 'data' => $category]);
    }

    public function deleteCategory($id)
    {
        $category = BudgetCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }


    // --- ATURAN ALOKASI ---
    // (Fungsi-fungsi untuk alokasi tidak ada perubahan)
    public function getAllocations()
    {
        return response()->json(BudgetAllocationRule::orderBy('id', 'asc')->get());
    }

    public function updateAllocation(Request $request, $id)
    {
        $rule = BudgetAllocationRule::find($id);
        if (!$rule) {
            return response()->json(['message' => 'Aturan alokasi tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $rule->update($validator->validated());
        return response()->json(['message' => 'Aturan alokasi berhasil diperbarui', 'data' => $rule]);
    }


    // --- DATA KOTA & BIAYA HIDUP ---

    public function getUmrData()
    {
        // FIX: Menggunakan nama kolom yang benar 'nama_kota' sesuai dengan database Anda
        return response()->json(UmrData::orderBy('nama_kota', 'asc')->get());
    }

    public function addUmrData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kota' => 'required|string|unique:cities,nama_kota',
            'provinsi' => 'required|string',
            'umr' => 'required|integer|min:0',
            'harga_makan_avg' => 'nullable|integer|min:0',
            'harga_kos_avg' => 'nullable|integer|min:0',
            'harga_transport_avg' => 'nullable|integer|min:0',
            'harga_paket_data_avg' => 'nullable|integer|min:0',
            'jumlah_hari_kuliah' => 'nullable|integer|min:0',
            'inflasi_tahunan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $umr = UmrData::create($validator->validated());
        return response()->json(['message' => 'Data kota berhasil ditambahkan', 'data' => $umr], 201);
    }

    public function updateUmrData(Request $request, $id)
    {
        $umr = UmrData::find($id);
        if (!$umr) {
            return response()->json(['message' => 'Data kota tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kota' => 'required|string|unique:cities,nama_kota,' . $id,
            'provinsi' => 'required|string',
            'umr' => 'required|integer|min:0',
            'harga_makan_avg' => 'nullable|integer|min:0',
            'harga_kos_avg' => 'nullable|integer|min:0',
            'harga_transport_avg' => 'nullable|integer|min:0',
            'harga_paket_data_avg' => 'nullable|integer|min:0',
            'jumlah_hari_kuliah' => 'nullable|integer|min:0',
            'inflasi_tahunan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $umr->update($validator->validated());
        return response()->json(['message' => 'Data kota berhasil diperbarui', 'data' => $umr]);
    }

    public function deleteUmrData($id)
    {
        $umr = UmrData::find($id);
        if (!$umr) {
            return response()->json(['message' => 'Data kota tidak ditemukan'], 404);
        }

        $umr->delete();
        return response()->json(['message' => 'Data kota berhasil dihapus']);
    }
}
