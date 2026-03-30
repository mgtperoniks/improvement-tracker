<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\WeeklyPlanController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Shared Routes (Dashboard & Rankings)
    Route::middleware(['role:admin,manager,director'])->group(function () {
        Route::get('/', [WeeklyPlanController::class, 'index'])->name('dashboard');
        Route::get('/rankings', [WeeklyPlanController::class, 'rankings'])->name('rankings');
    });
    
    // Admin Only (Operational)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/weekly-plan/create', [WeeklyPlanController::class, 'create'])->name('weekly-plans.create');
        Route::get('/weekly-plan/closing', [WeeklyPlanController::class, 'closing'])->name('weekly-plans.closing');
        Route::post('/api/weekly-plans', [WeeklyPlanController::class, 'store'])->name('api.weekly-plans.store');
        Route::patch('/api/weekly-plans/{plan}/status', [WeeklyPlanController::class, 'updateStatus'])->name('api.weekly-plans.update-status');
    });
});
