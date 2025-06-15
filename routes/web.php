<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\User;
use App\Models\UmrData;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminBudgetingController;

/*
|--------------------------------------------------------------------------
| Public Web & API Routes
|--------------------------------------------------------------------------
*/

// Rute untuk menampilkan halaman publik
Route::get('/', function () { return view('index'); });
Route::get('/home', function () { return view('home'); });
Route::get('/sign-up', function () { return view('sign_up'); });
Route::get('/sign-in', function () { return view('sign_in'); });
Route::get('/budgeting', function () { return view('budgeting'); });
Route::get('/literasi-keuangan', function () { return view('literasi_keuangan'); });
Route::get('/profile', function () { return view('profile'); });

// Rute API publik
Route::post('/sync-user', [UserController::class, 'syncUser']);
Route::get('/get-universities', function () {
    return response()->json(University::select('id', 'name')->get());
});

Route::prefix('api')->group(function () {
    Route::get('/cities', function() {
        return response()->json(DB::table('cities')->orderBy('nama_kota', 'asc')->pluck('nama_kota'));
    })->name('api.cities.index');

    Route::get('/budget', [BudgetController::class, 'show'])->name('api.budget.show');
    Route::post('/budget', [BudgetController::class, 'storeOrUpdate'])->name('api.budget.store');
    
    Route::get('/articles', [ArticleController::class, 'index'])->name('api.articles.index');
    Route::get('/articles/{section_id}', [ArticleController::class, 'show'])->name('api.articles.show');
    
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
            'phone' => $user->phone ?? null,
        ]);
    });
});


/*
|--------------------------------------------------------------------------
| Admin Routes (Struktur Disederhanakan dengan Nama Rute Eksplisit)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // --- Admin View Routes ---
    Route::get('/dashboard', function () { return view('admin_dashboard'); })->name('admin.dashboard');
    
    // Artikel
    Route::get('/articles', function () { return view('admin_literasi_keuangan'); })->name('admin.articles.index');
    Route::get('/articles/{section_id}/edit', function ($section_id) { 
        return view('edit_article', ['section_id' => $section_id]); 
    })->name('admin.articles.edit');
    
    // Budgeting
    Route::get('/budget', function () { return view('admin_budgeting'); })->name('admin.budgeting.index');
    Route::get('/budget/categories', function () { return view('admin_budget_categories'); })->name('admin.budgeting.categories');
    Route::get('/budget/allocations', function () { return view('admin_budget_allocations'); })->name('admin.budgeting.allocations');
    Route::get('/budget/umr', function () { return view('admin_budget_umr'); })->name('admin.budgeting.umr');
    
    // Profile
    Route::get('/edit-profile', function () { return view('edit_profile'); })->name('admin.profile.edit');

    // --- Admin API Routes ---
    Route::prefix('api')->group(function() {

        Route::get('/users', [UserController::class, 'getDashboardData'])->name('admin.api.users');
        Route::post('/articles/{section_id}', [ArticleController::class, 'update'])->name('admin.api.articles.update');

        Route::prefix('budget')->group(function() {
            Route::get('/categories', [AdminBudgetingController::class, 'getCategories'])->name('admin.api.budget.categories.index');
            Route::post('/categories', [AdminBudgetingController::class, 'addCategory'])->name('admin.api.budget.categories.store');
            Route::put('/categories/{id}', [AdminBudgetingController::class, 'updateCategory'])->name('admin.api.budget.categories.update');
            Route::delete('/categories/{id}', [AdminBudgetingController::class, 'deleteCategory'])->name('admin.api.budget.categories.destroy');

            Route::get('/allocations', [AdminBudgetingController::class, 'getAllocations'])->name('admin.api.budget.allocations.index');
            Route::put('/allocations/{id}', [AdminBudgetingController::class, 'updateAllocation'])->name('admin.api.budget.allocations.update');
            
            Route::get('/umr', [AdminBudgetingController::class, 'getUmrData'])->name('admin.api.budget.umr.index');
            Route::post('/umr', [AdminBudgetingController::class, 'addUmrData'])->name('admin.api.budget.umr.store');
            Route::put('/umr/{id}', [AdminBudgetingController::class, 'updateUmrData'])->name('admin.api.budget.umr.update');
            Route::delete('/umr/{id}', [AdminBudgetingController::class, 'deleteUmrData'])->name('admin.api.budget.umr.destroy');
        });
    });
});

?>
