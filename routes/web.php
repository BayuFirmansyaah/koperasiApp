<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Anggota\AnggotaController;
use App\Http\Controllers\Pengurus\ApprovalController;
use App\Http\Controllers\Simpanan\SimpananController;
use App\Http\Controllers\Pinjaman\PinjamanController;
use App\Http\Controllers\Angsuran\AngsuranController;
use App\Http\Controllers\Kas\KasController;
use App\Http\Controllers\Laporan\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul Anggota
    Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);

    // Modul Approval (untuk pengurus)
    Route::prefix('pengurus')->name('pengurus.')->group(function () {
        Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
        Route::post('/approval/{anggota}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('/approval/{anggota}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    });

    // Modul Simpanan
    Route::resource('simpanan', SimpananController::class)->parameters(['simpanan' => 'simpanan']);
    Route::get('/simpanan-verify', [SimpananController::class, 'verify'])->name('simpanan.verify');
    Route::post('/simpanan/{simpanan}/verify', [SimpananController::class, 'doVerify'])->name('simpanan.doVerify');

    // Modul Pinjaman
    Route::resource('pinjaman', PinjamanController::class)->parameters(['pinjaman' => 'pinjaman']);
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/review', [PinjamanController::class, 'review'])->name('review');
        Route::post('/{pinjaman}/review', [PinjamanController::class, 'doReview'])->name('doReview');
        Route::get('/approve', [PinjamanController::class, 'approve'])->name('approve');
        Route::post('/{pinjaman}/approve', [PinjamanController::class, 'doApprove'])->name('doApprove');
        Route::get('/disburse', [PinjamanController::class, 'disburse'])->name('disburse');
        Route::post('/{pinjaman}/disburse', [PinjamanController::class, 'doDisburse'])->name('doDisburse');
    });

    // Modul Angsuran
    Route::resource('angsuran', AngsuranController::class)->parameters(['angsuran' => 'angsuran']);
    Route::get('/angsuran-verify', [AngsuranController::class, 'verify'])->name('angsuran.verify');
    Route::post('/angsuran/{angsuran}/verify', [AngsuranController::class, 'doVerify'])->name('angsuran.doVerify');

    // Modul Kas
    Route::resource('kas', KasController::class)->parameters(['kas' => 'ka']);
    Route::get('/kas-laporan', [KasController::class, 'laporan'])->name('kas.laporan');

    // Modul Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/anggota', [LaporanController::class, 'anggota'])->name('anggota');
        Route::get('/rekening-koran/{anggota}', [LaporanController::class, 'rekeningKoran'])->name('rekeningKoran');
    });
});

require __DIR__.'/auth.php';


