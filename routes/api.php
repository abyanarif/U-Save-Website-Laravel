<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute API untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| diberi grup middleware "api". Buat sesuatu yang hebat!
|
*/

// --- Rute untuk Budgeting ---
// Gunakan middleware 'auth.firebase' agar user diverifikasi menggunakan Firebase
Route::middleware(['auth.firebase'])->group(function () {
    // Ambil data budget milik user
    Route::get('/budget', [BudgetController::class, 'show'])->name('budget.show');

    // Simpan atau update data budget milik user
    Route::post('/budget', [BudgetController::class, 'storeOrUpdate'])->name('budget.storeOrUpdate');
});

/*
// Contoh rute sanctum (jika ingin pakai sanctum, aktifkan dan sesuaikan)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*
// Rute tes tanpa autentikasi - hanya untuk debugging
Route::get('/budget-test', [BudgetController::class, 'show'])->name('budget.show.test');
Route::post('/budget-test', [BudgetController::class, 'storeOrUpdate'])->name('budget.storeOrUpdate.test');
*/
