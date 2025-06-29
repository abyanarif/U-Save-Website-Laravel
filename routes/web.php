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
Route::get('/edit-profile', function () { return view('edit_profile'); });

// Rute API publik
Route::post('/sync-user', [UserController::class, 'syncUser']);
Route::get('/get-universities', function () {
    return response()->json(University::select('id', 'name')->get());
});

Route::prefix('api')->group(function () {
    Route::get('/cities', function() {
        return response()->json(DB::table('cities')->orderBy('nama_kota', 'asc')->pluck('nama_kota'));
    })->name('api.cities.index');

    // Diasumsikan rute ini memerlukan autentikasi
    Route::get('/budget', [BudgetController::class, 'show'])->name('api.budget.show');
    Route::post('/budget', [BudgetController::class, 'storeOrUpdate'])->name('api.budget.store');
    
    Route::get('/articles', [ArticleController::class, 'index'])->name('api.articles.index');
    Route::get('/articles/{section_id}', [ArticleController::class, 'show'])->name('api.articles.show');
    
    Route::get('/user-profile', [UserController::class, 'getUserProfile'])->name('api.user.profile');
    Route::post('/user-profile', [UserController::class, 'updateUserProfile'])->name('api.user.profile.update');
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
    
    // FIX: Mengganti rute lama /budget/edit menjadi /budget/umr agar sesuai
    Route::get('/budget/umr', function () { return view('admin_budget_umr'); })->name('admin.budgeting.umr');

    // Profile
    Route::get('/profile', function () { return view('admin_profile'); })->name('admin.profile');

    // --- Admin API Routes ---
    Route::prefix('api')->group(function() {

        Route::get('/users', [UserController::class, 'getDashboardData'])->name('admin.api.users');
        Route::put('/articles/{section_id}', [ArticleController::class, 'update'])->name('admin.api.articles.update');
        Route::get('/profile', [UserController::class, 'getAdminProfile'])->name('admin.api.profile');

        Route::prefix('budget')->group(function() {
            Route::get('/categories', [AdminBudgetingController::class, 'getCategories'])->name('admin.api.budget.categories');
            Route::post('/categories', [AdminBudgetingController::class, 'addCategory']);
            Route::put('/categories/{id}', [AdminBudgetingController::class, 'updateCategory']);
            Route::delete('/categories/{id}', [AdminBudgetingController::class, 'deleteCategory']);

            Route::get('/allocations', [AdminBudgetingController::class, 'getAllocations'])->name('admin.api.budget.allocations');
            Route::put('/allocations/{id}', [AdminBudgetingController::class, 'updateAllocation']);
            
            Route::get('/umr', [AdminBudgetingController::class, 'getUmrData'])->name('admin.api.budget.umr');
            Route::post('/umr', [AdminBudgetingController::class, 'addUmrData']);
            Route::put('/umr/{id}', [AdminBudgetingController::class, 'updateUmrData']);
            Route::delete('/umr/{id}', [AdminBudgetingController::class, 'deleteUmrData']);
        });
    });
});

?>
