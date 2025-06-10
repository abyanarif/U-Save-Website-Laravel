<?php

use Illuminate\Support\Facades\Route;
use App\Models\University;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ArticleController; // Dipindahkan ke atas

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Rute untuk Menampilkan Halaman (Views) ---
Route::get('/', function () { return view('index'); });
Route::get('/laravel', function () { return view('welcome'); });
Route::get('/home', function () { return view('home'); });
Route::get('/sign-up', function () { return view('sign_up'); });
Route::get('/sign-in', function () { return view('sign_in'); });
Route::get('/budgeting', function () { return view('budgeting'); });
Route::get('/literasi-keuangan', function () { return view('literasi_keuangan'); });
Route::get('/profile', function () { return view('profile'); });
Route::get('/dashboard', function () { return view('admin_dashboard'); });
Route::get('/edit-profile', function () { return view('edit_profile'); });
Route::get('/edit-article', function () { return view('edit_article'); }); // Rute baru Anda sudah ada di sini

// --- Rute Fungsional dan API (Non-Grup) ---
Route::get('/get-universities', function () {
    return response()->json(University::select('id', 'name')->get());
});

Route::post('/store-user', function (Request $request) {
    $validated = $request->validate([
        'uid' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|string|unique:users,username',
        'university_id' => 'nullable|exists:universities,id',
    ]);
    User::create([
        'firebase_uid' => $validated['uid'],
        'username' => $validated['username'],
        'email' => $validated['email'],
        'university_id' => $validated['university_id'],
        'password' => '',
    ]);
    return response()->json(['message' => 'User berhasil disimpan'], 201);
});

Route::post('/sync-user', [UserController::class, 'syncUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- Grup Rute API (/api/...) ---
// Rute API yang sudah ada kita biarkan di dalam grup
Route::group(['prefix' => 'api'], function () {

    // Rute API untuk Budgeting
    Route::get('/budget', [BudgetController::class, 'show'])->name('api.budget.show');
    Route::post('/budget', [BudgetController::class, 'storeOrUpdate'])->name('api.budget.store');

    // Rute API untuk User Profile
    Route::get('/user-profile', function (Request $request) {
        $firebaseUid = $request->query('firebase_uid');
        if (!$firebaseUid) {
            return response()->json(['error' => 'Parameter firebase_uid dibutuhkan'], 400);
        }
        $user = User::where('firebase_uid', $firebaseUid)->with('university')->first();
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'university' => $user->university->name ?? null,
        ]);
    });

});

// --- Rute API untuk Artikel Literasi Keuangan (didefinisikan secara individual) ---
Route::get('/api/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/api/articles/{section_id}', [ArticleController::class, 'show'])->name('articles.show');
// PERHATIAN: Middleware autentikasi admin DIHAPUS SEMENTARA untuk pengujian.
Route::post('/api/articles/{section_id}', [ArticleController::class, 'update'])->name('articles.update');

?>
