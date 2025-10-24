<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Awal (Login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [SurveyController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile User
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Statistik & Peta Potensi
    |--------------------------------------------------------------------------
    */
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

    // 🔹 API routes (semua masih bisa diakses setelah login)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/desas', [StatsController::class, 'getDesas'])->name('desas');
        Route::get('/jenis-potensi', [StatsController::class, 'getJenisPotensi'])->name('jenis_potensi');
        Route::get('/produksi', [StatsController::class, 'getProduksi'])->name('produksi');
        Route::get('/komoditas', [StatsController::class, 'getAllKomoditas'])->name('komoditas');
        Route::get('/desa-by-komoditas', [StatsController::class, 'getDesaByKomoditas'])->name('desa_by_komoditas');
    });

    /*
    |--------------------------------------------------------------------------
    | CRUD Survey
    |--------------------------------------------------------------------------
    */
    Route::prefix('surveys')->name('surveys.')->group(function () {
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::get('/create', [SurveyController::class, 'create'])->name('create');
        Route::post('/', [SurveyController::class, 'store'])->name('store');
        Route::get('/voice', [SurveyController::class, 'createvoice'])->name('voicecreate');
    });
});

require __DIR__ . '/auth.php';
