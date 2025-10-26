<?php

use App\Http\Controllers\ECommerce\DemandController;
use App\Http\Controllers\ECommerce\SupplyController;
use App\Http\Controllers\ECommerce\ViewController;
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

    // E-Commerce View
    Route::get('e-commerce/market', [ViewController::class, 'index'])->name('e-commerce.market');
    Route::post('get-kelurahan', [SupplyController::class, 'getKelurahan'])->name('getKelurahan');

    // Supply Barang
    Route::get('e-commerce/market/suply', [SupplyController::class, 'index'])->name('e-commerce.suply');
    Route::get('e-commerce/market/suply/create', [SupplyController::class, 'create'])->name('e-commerce.suply.create');
    Route::post('e-commerce/market/suply/store', [SupplyController::class, 'store'])->name('e-commerce.suply.store');
    Route::get('e-commerce/market/suply/edit/{id}', [SupplyController::class, 'edit'])->name('e-commerce.suply.edit');
    Route::put('e-commerce/market/suply/update/{id}', [SupplyController::class, 'update'])->name('e-commerce.suply.update');
    Route::delete('e-commerce/market/suply/delete/{id}', [SupplyController::class, 'delete'])->name('e-commerce.suply.delete');
    Route::post('e-commerce/market/suply/posting/{id}', [SupplyController::class, 'posting'])
        ->name('e-commerce.suply.posting');

    // Demand Barang
    Route::get('e-commerce/market/demand', [DemandController::class, 'index'])->name('e-commerce.demand');
    Route::get('e-commerce/market/demand/create', [DemandController::class, 'create'])->name('e-commerce.demand.create');
    Route::post('e-commerce/market/demand/store', [DemandController::class, 'store'])->name('e-commerce.demand.store');
    Route::get('e-commerce/market/demand/edit/{id}', [DemandController::class, 'edit'])->name('e-commerce.demand.edit');
    Route::put('e-commerce/market/demand/update/{id}', [DemandController::class, 'update'])->name('e-commerce.demand.update');
    Route::delete('e-commerce/market/demand/delete/{id}', [DemandController::class, 'delete'])->name('e-commerce.demand.delete');
    Route::post('e-commerce/market/demand/post/{id}', [DemandController::class, 'posting'])
        ->name('e-commerce.demand.post');
});


require __DIR__ . '/auth.php';
