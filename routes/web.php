<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\WeeklyPlanController;
use App\Http\Controllers\WeeklyReportController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Shared Routes (Dashboard & Rankings)
    Route::middleware(['role:admin,manager,director'])->group(function () {
        Route::get('/', [WeeklyPlanController::class, 'index'])->name('dashboard');
        Route::get('/rankings', [WeeklyPlanController::class, 'rankings'])->name('rankings');
    });

    // Weekly Reporting for MR and Director
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/weekly-reports', [WeeklyReportController::class, 'index'])->name('weekly-reports.index');
        Route::get('/weekly-reports/{week_start_date}', [WeeklyReportController::class, 'show'])->name('weekly-reports.show');
        
        // Plan Edit/Delete
        Route::get('/weekly-reports/plans/{plan}/edit', [WeeklyReportController::class, 'edit'])->name('weekly-reports.plans.edit');
        Route::put('/weekly-reports/plans/{plan}', [WeeklyReportController::class, 'update'])->name('weekly-reports.plans.update');
        Route::delete('/weekly-reports/plans/{plan}', [WeeklyReportController::class, 'destroy'])->name('weekly-reports.plans.destroy');
    });
    
    // Admin Only (Operational)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/weekly-plan/create', [WeeklyPlanController::class, 'create'])->name('weekly-plans.create');
        Route::get('/weekly-plan/closing', [WeeklyPlanController::class, 'closing'])->name('weekly-plans.closing');
        Route::post('/weekly-plan', [WeeklyPlanController::class, 'store'])->name('weekly-plans.store');
        Route::patch('/api/weekly-plans/{plan}/status', [WeeklyPlanController::class, 'updateStatus'])->name('api.weekly-plans.update-status');

        // Data Master
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        });
    });
});
