<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\PetugasController;
use App\Http\Controllers\Dashboard\OwnerController;
use App\Http\Controllers\ParkirController;
use App\Http\Controllers\PetugasController as ManagePetugasController;
use App\Http\Controllers\AreaParkirController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LaporanController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth', 'active'])->group(function () {
    
    // Dashboard routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard')->middleware('role:petugas');
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard')->middleware('role:owner');

    // Admin management routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Petugas management
        Route::get('/petugas', [ManagePetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas', [ManagePetugasController::class, 'store'])->name('petugas.store');
        Route::put('/petugas/{id}', [ManagePetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{id}', [ManagePetugasController::class, 'destroy'])->name('petugas.destroy');

        // Area parkir management
        Route::get('/area', [AreaParkirController::class, 'index'])->name('area.index');
        Route::post('/area', [AreaParkirController::class, 'store'])->name('area.store');
        Route::put('/area/{id}', [AreaParkirController::class, 'update'])->name('area.update');
        Route::delete('/area/{id}', [AreaParkirController::class, 'destroy'])->name('area.destroy');

        // Tarif management
        Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
        Route::post('/tarif', [TarifController::class, 'store'])->name('tarif.store');
        Route::put('/tarif/{id}', [TarifController::class, 'update'])->name('tarif.update');

        // Kendaraan management
        Route::get('/kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');
    });

    // Parkir routes (for petugas)
    Route::middleware('role:petugas')->prefix('parkir')->name('parkir.')->group(function () {
        Route::get('/masuk', [ParkirController::class, 'formMasuk'])->name('masuk');
        Route::post('/masuk', [ParkirController::class, 'masuk']);
        Route::get('/print/{id}', [ParkirController::class, 'printKarcis'])->name('print');
        Route::get('/keluar', [ParkirController::class, 'formKeluar'])->name('keluar');
        Route::post('/keluar/scan', [ParkirController::class, 'scanBarcode'])->name('keluar.scan');
        Route::post('/keluar/manual', [ParkirController::class, 'keluarManual'])->name('keluar.manual');
        Route::post('/keluar/proses', [ParkirController::class, 'prosesKeluar'])->name('keluar.proses');
        Route::get('/riwayat', [ParkirController::class, 'riwayat'])->name('riwayat');
    });

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
