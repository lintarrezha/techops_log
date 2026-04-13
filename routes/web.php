<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityTypeController as AdminActivityTypeController;
use App\Http\Controllers\Admin\ChecklistTemplateController as AdminChecklistTemplateController;


// Redirect root ke dashboard
Route::get('/', fn() => redirect()->route('dashboard'));
// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Manajemen Jenis Kegiatan
    Route::get('/', fn() => redirect()->route('admin.activity-types.index'));
    Route::resource('activity-types', AdminActivityTypeController::class)
        ->except(['show']);
    Route::patch('activity-types/{activityType}/toggle', [AdminActivityTypeController::class, 'toggleActive'])
        ->name('activity-types.toggle');

    // Manajemen Template Checklist
    Route::prefix('activity-types/{activityType}/templates')->name('checklist-templates.')->group(function () {
        Route::get('/',                                          [AdminChecklistTemplateController::class, 'index'])->name('index');
        Route::get('/create',                                    [AdminChecklistTemplateController::class, 'create'])->name('create');
        Route::post('/',                                         [AdminChecklistTemplateController::class, 'store'])->name('store');
        Route::get('/{checklistTemplate}/edit',                  [AdminChecklistTemplateController::class, 'edit'])->name('edit');
        Route::put('/{checklistTemplate}',                       [AdminChecklistTemplateController::class, 'update'])->name('update');
        Route::delete('/{checklistTemplate}',                    [AdminChecklistTemplateController::class, 'destroy'])->name('destroy');
        Route::patch('/{checklistTemplate}/move-up',             [AdminChecklistTemplateController::class, 'moveUp'])->name('move-up');
        Route::patch('/{checklistTemplate}/move-down',           [AdminChecklistTemplateController::class, 'moveDown'])->name('move-down');
    });
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Input & Riwayat Kegiatan
    Route::prefix('kegiatan')->name('activity.')->group(function () {
        Route::get('/',                          [ActivityLogController::class, 'index'])->name('index');
        Route::get('/buat',                      [ActivityLogController::class, 'create'])->name('create');
        Route::post('/',                         [ActivityLogController::class, 'store'])->name('store');
        Route::get('/{activityLog}',             [ActivityLogController::class, 'show'])->name('show');
        Route::get('/checklist/{activityType}',  [ActivityLogController::class, 'getChecklist'])->name('checklist');

        Route::get('/{activityLog}/export-pdf',  [ActivityLogController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{activityLog}/export-word', [ActivityLogController::class, 'exportWord'])->name('export.word');
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
