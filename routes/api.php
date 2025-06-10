<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController; // Pastikan namespace Controller Anda benar

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

// Contoh rute default yang mungkin sudah ada (jika menggunakan Sanctum atau Passport)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// --- Rute untuk Budgeting ---

// PENTING: Anda memerlukan middleware untuk memverifikasi Firebase ID Token
// dan mengautentikasi pengguna sebelum mengakses rute-rute ini.
// 'auth.firebase' di bawah ini adalah NAMA CONTOH untuk middleware tersebut.
// Anda mungkin perlu membuat middleware ini atau menggunakan package yang ada.

Route::middleware(['auth.firebase'])->group(function () {
    /**
     * Mengambil data budget plan milik pengguna yang terautentikasi.
     * Method: GET
     * URL: /api/budget
     */
    Route::get('/budget', [BudgetController::class, 'show'])->name('budget.show');

    /**
     * Menyimpan atau memperbarui budget plan milik pengguna yang terautentikasi.
     * Method: POST
     * URL: /api/budget
     */
    Route::post('/budget', [BudgetController::class, 'storeOrUpdate'])->name('budget.storeOrUpdate');
});


// --- CATATAN UNTUK PENGUJIAN AWAL (HAPUS ATAU BERI KOMENTAR SETELAHNYA) ---
// Jika Anda BELUM memiliki middleware 'auth.firebase' dan hanya ingin menguji
// apakah rute dan controller berfungsi TANPA autentikasi, Anda bisa
// mendefinisikannya sementara seperti ini.
// PERINGATAN: Ini TIDAK AMAN untuk produksi karena tidak ada autentikasi.
/*
Route::get('/budget-test', [BudgetController::class, 'show'])->name('budget.show.test');
Route::post('/budget-test', [BudgetController::class, 'storeOrUpdate'])->name('budget.storeOrUpdate.test');
*/
// Jika menggunakan rute tes di atas, pastikan frontend Anda memanggil /api/budget-test
// dan BudgetController Anda disesuaikan untuk menangani user secara manual jika perlu untuk tes.