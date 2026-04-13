<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Redirect root ke dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Input & Riwayat Kegiatan
    Route::prefix('kegiatan')->name('activity.')->group(function () {
        Route::get('/',         [ActivityLogController::class, 'index'])->name('index');
        Route::get('/buat',     [ActivityLogController::class, 'create'])->name('create');
        Route::post('/',        [ActivityLogController::class, 'store'])->name('store');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');

        // AJAX: load checklist berdasarkan jenis kegiatan
        Route::get('/checklist/{activityType}', [ActivityLogController::class, 'getChecklist'])
            ->name('checklist');
    });

    // Knowledge Base
    Route::prefix('knowledge-base')->name('kb.')->group(function () {
        Route::get('/',                    [KnowledgeBaseController::class, 'index'])->name('index');
        Route::post('/',                   [KnowledgeBaseController::class, 'store'])->name('store');
        Route::patch('/{issue}/resolve',   [KnowledgeBaseController::class, 'resolve'])->name('resolve');
    });

    // Laporan
    Route::prefix('laporan')->name('report.')->group(function () {
        Route::get('/',           [ReportController::class, 'index'])->name('index');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('pdf');
    });
});

require __DIR__ . '/auth.php';
