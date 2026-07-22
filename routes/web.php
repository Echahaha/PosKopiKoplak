<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LstmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SalesHistoryController;


// ==========================================
// 1. GUEST ROUTES (Sebelum Login)
// ==========================================
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // ── PASSWORD RESET ────────────────────────────────
    Route::get('/lupa-sandi', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-sandi', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-sandi/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-sandi', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==========================================
// 2. AUTH ROUTES (Harus Login Dulu)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & POS (Bisa diakses Owner & Barista)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/status', [DashboardController::class, 'statusJson'])
        ->name('dashboard.status');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/print/{id}', [PosController::class, 'printReceipt'])->name('pos.print');

    // Manajemen Produk & Logs
    Route::get('/products/logs', [ProductController::class, 'stockLogs'])->name('products.logs');
    Route::resource('products', ProductController::class);

    // Manajemen Pengeluaran (DIPINDAH KE SINI: Barista & Owner bisa akses)
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);


    // ==========================================
    // 3. KHUSUS ROLE: OWNER (Eca)
    // ==========================================
    // Ganti 'can:access-owner-features' jadi 'role:owner'
    Route::middleware(['role:owner'])->group(function () {

        // Laporan & Export Data (Dataset LSTM)
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportCSV'])->name('reports.export');

        // Fitur Void & Update Pembayaran
        Route::post('/transactions/{id}/update-payment', [PosController::class, 'updatePaymentMethod'])->name('transactions.updatePayment');
        Route::delete('/transactions/{id}/void', [PosController::class, 'void'])->name('transactions.void');
        Route::post('/products/restock', [ProductController::class, 'restock'])->name('products.restock');

        // Resep
        Route::get('/products/{product}/recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::post('/products/{product}/recipes', [RecipeController::class, 'store'])->name('recipes.store');
        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

        // Route untuk Halaman Prediksi LSTM
        Route::get('/prediksi-lstm', [LstmController::class, 'index'])->name('lstm.index');
        Route::post('/prediksi-lstm/hitung', [LstmController::class, 'hitungPrediksi'])->name('lstm.hitung');
        Route::get('/dashboard-stok', [LstmController::class, 'dashboardStok'])->name('lstm.dashboard-stok');

        // Pengaturan: Kelola User/Barista
        Route::get('/pengaturan', [UserController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/pengaturan/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/pengaturan/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
        Route::delete('/pengaturan/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/masterdata', [MasterDataController::class, 'index'])->name('masterdata.index');

        // CRUD Kategori (dipakai dari dalam tab Kategori di halaman masterdata)
        Route::post('/masterdata/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/masterdata/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/masterdata/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


        // ── Stok Opname ──
        Route::get('/stok-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::post('/stok-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
        Route::get('/stok-opname/{id}', [StockOpnameController::class, 'show'])->name('stock-opname.show');
        Route::put('/stok-opname/{id}/details', [StockOpnameController::class, 'updateDetails'])->name('stock-opname.updateDetails');
        Route::post('/stok-opname/{id}/finish', [StockOpnameController::class, 'finish'])->name('stock-opname.finish');

        // ── Import Data Kasir Pintar ──
        Route::get('/sales-history', [SalesHistoryController::class, 'index'])->name('sales-history.index');
        Route::post('/sales-history/upload', [SalesHistoryController::class, 'upload'])->name('sales-history.upload');
        Route::delete('/sales-history', [SalesHistoryController::class, 'destroy'])->name('sales-history.destroy');
        Route::delete('/stok-opname/{id}', [StockOpnameController::class, 'destroy'])->name('stock-opname.destroy');
    });
});
