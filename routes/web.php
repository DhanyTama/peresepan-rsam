<?php

use App\Http\Controllers\Apoteker\ObatController;
use App\Http\Controllers\Apoteker\TransaksiController;
use App\Http\Controllers\Dokter\PasienController;
use App\Http\Controllers\Dokter\ResepController;
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

Route::get('/', function () {
    // return view('welcome');
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:dokter')->group(function () {
        Route::resource('/pasien', PasienController::class)->only(['index']);
        Route::resource('/resep', ResepController::class);
    });

    Route::middleware('role:apoteker')->group(function () {
        Route::resource('/obat', ObatController::class);
        Route::resource('/transaksi', TransaksiController::class);
    });
});

require __DIR__ . '/auth.php';
