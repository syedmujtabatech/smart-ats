<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobPostController;
use Illuminate\Support\Facades\Route;

// Home redirect to careers or dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Public Careers Portal
Route::prefix('careers')->name('careers.')->group(function () {
    Route::get('/', [CareerController::class, 'index'])->name('index');
    Route::get('/application/{application}/success', [CareerController::class, 'success'])->name('success');
    Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
    Route::post('/{slug}/apply', [CareerController::class, 'apply'])->name('apply');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/login/demo', [AuthController::class, 'demoLogin'])->name('login.demo');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin & HR Management Portal
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Jobs
    Route::resource('jobs', JobPostController::class);

    // Applications & ATS
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/kanban', [ApplicationController::class, 'kanban'])->name('applications.kanban');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/stage', [ApplicationController::class, 'updateStage'])->name('applications.stage');
    Route::post('/applications/{application}/re-analyze', [ApplicationController::class, 'reAnalyzeAi'])->name('applications.reanalyze');
    Route::post('/applications/{application}/interviews', [ApplicationController::class, 'scheduleInterview'])->name('applications.interview');
    Route::post('/applications/{application}/notes', [ApplicationController::class, 'saveNotes'])->name('applications.notes');
});
