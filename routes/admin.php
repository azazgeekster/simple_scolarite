<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamPeriodController;
use App\Http\Controllers\Admin\ExamImportController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Auth\AdminResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group.
|
*/

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest (not logged in) admin routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('loginform');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login');

        // Password Reset Routes
        Route::get('forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [AdminResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::redirect('/', '/admin/dashboard'); // Redirect /admin → /admin/dashboard
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Exam Periods Management
        Route::prefix('exam-periods')->name('exam-periods.')->group(function () {
            Route::get('/', [ExamPeriodController::class, 'index'])->name('index');
            Route::get('/create', [ExamPeriodController::class, 'create'])->name('create');
            Route::post('/', [ExamPeriodController::class, 'store'])->name('store');
            Route::get('/{examPeriod}/edit', [ExamPeriodController::class, 'edit'])->name('edit');
            Route::put('/{examPeriod}', [ExamPeriodController::class, 'update'])->name('update');
            Route::delete('/{examPeriod}', [ExamPeriodController::class, 'destroy'])->name('destroy');

            // Special actions
            Route::post('/{examPeriod}/activate', [ExamPeriodController::class, 'activate'])->name('activate');
            Route::post('/{examPeriod}/deactivate', [ExamPeriodController::class, 'deactivate'])->name('deactivate');
            Route::post('/{examPeriod}/publish-exams', [ExamPeriodController::class, 'publishExams'])->name('publish-exams');
            Route::post('/{examPeriod}/unpublish-exams', [ExamPeriodController::class, 'unpublishExams'])->name('unpublish-exams');
        });

        // Exam import routes (Super Admin only)
        Route::get('exams/import', [ExamImportController::class, 'showImportForm'])->name('exams.import');
        Route::post('exams/import', [ExamImportController::class, 'import'])->name('exams.import.process');
        Route::get('exams/download-template', [ExamImportController::class, 'downloadTemplate'])->name('exams.download-template');
        Route::post('exams/export-rattrapage-candidates', [ExamImportController::class, 'exportRattrapageCandidates'])->name('exams.export-rattrapage-candidates');
        Route::post('exams/toggle-session-publication', [ExamImportController::class, 'toggleSessionPublication'])->name('exams.toggle-session-publication');

        // Messages
        Route::resource('messages', MessageController::class)->except(['edit', 'update']);
    });
});

// Role & permission management routes (requires role:Admin and auth:admin)
Route::middleware(['auth:admin', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.delete');

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.delete');
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole'])->name('roles.give_permissions_form');
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole'])->name('roles.give_permissions');

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.delete');
});
