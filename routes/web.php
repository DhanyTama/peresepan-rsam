<?php

use App\Http\Controllers\Apoteker\ObatController;
use App\Http\Controllers\Apoteker\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dokter\PasienController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', 'login');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',   [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/resep', [ResepController::class, 'index'])->name('resep.index');

    Route::middleware('role:dokter')->group(function () {
        Route::resource('/pasien', PasienController::class)->only(['index']);
        Route::resource('/resep', ResepController::class)->except(['index']);
    });

    Route::middleware('role:apoteker')->group(function () {
        Route::resource('/obat', ObatController::class);
        Route::post('/resep/{resep}/process', [ResepController::class, 'process'])->name('resep.process');
        Route::resource('/transaksi', TransaksiController::class);
    });
});

require __DIR__ . '/auth.php';
