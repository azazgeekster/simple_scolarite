<?php

use App\Http\Controllers\Admin\AcademicManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentRequestController;
use App\Http\Controllers\Admin\ExamPeriodController;
use App\Http\Controllers\Admin\ExamImportController;
use App\Http\Controllers\Admin\ExamSchedulingController;
use App\Http\Controllers\Admin\GradeManagementController;
use App\Http\Controllers\Admin\LocalManagementController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ModuleProfessorManagementController;
use App\Http\Controllers\Admin\ProfileChangeRequestController;
use App\Http\Controllers\Admin\StudentManagementController;
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

        // API endpoints (must be before exam-scheduling routes to avoid conflicts)
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/modules', [ExamSchedulingController::class, 'getModules'])->name('modules');
        });

        // Exam Scheduling (Phase 1)
        Route::prefix('exam-scheduling')->name('exam-scheduling.')->group(function () {
            Route::get('/', [ExamSchedulingController::class, 'index'])->name('index');
            Route::get('/create', [ExamSchedulingController::class, 'create'])->name('create');
            Route::post('/', [ExamSchedulingController::class, 'store'])->name('store');
            Route::get('/{exam}/room-allocation', [ExamSchedulingController::class, 'roomAllocation'])->name('room-allocation');
            Route::post('/{exam}/allocate-rooms', [ExamSchedulingController::class, 'allocateRooms'])->name('allocate-rooms');
            Route::post('/{exam}/rebalance-rooms', [ExamSchedulingController::class, 'rebalanceRooms'])->name('rebalance-rooms');
            Route::delete('/{exam}', [ExamSchedulingController::class, 'destroy'])->name('destroy');
            Route::get('/{exam}/download-pv', [ExamSchedulingController::class, 'downloadPV'])->name('download-pv');
        });

        // Local (Room) Management
        Route::resource('locals', LocalManagementController::class);

        // Exam import routes (Super Admin only)
        Route::get('exams/import', [ExamImportController::class, 'showImportForm'])->name('exams.import');
        Route::post('exams/import', [ExamImportController::class, 'import'])->name('exams.import.process');
        Route::get('exams/download-template', [ExamImportController::class, 'downloadTemplate'])->name('exams.download-template');
        Route::post('exams/export-rattrapage-candidates', [ExamImportController::class, 'exportRattrapageCandidates'])->name('exams.export-rattrapage-candidates');
        Route::post('exams/toggle-session-publication', [ExamImportController::class, 'toggleSessionPublication'])->name('exams.toggle-session-publication');

        // Messages
        Route::resource('messages', MessageController::class)->except(['edit', 'update']);

        // Profile Change Requests
        Route::prefix('profile-change-requests')->name('profile-change-requests.')->group(function () {
            Route::get('/', [ProfileChangeRequestController::class, 'index'])->name('index');
            Route::get('/history', [ProfileChangeRequestController::class, 'history'])->name('history');
            Route::get('/{student}', [ProfileChangeRequestController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [ProfileChangeRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [ProfileChangeRequestController::class, 'reject'])->name('reject');
            Route::post('/{student}/approve-all', [ProfileChangeRequestController::class, 'approveAll'])->name('approve-all');
            Route::post('/{student}/reject-all', [ProfileChangeRequestController::class, 'rejectAll'])->name('reject-all');
        });

        // Document Requests Management
        Route::prefix('document-requests')->name('document-requests.')->group(function () {
            Route::get('/', [DocumentRequestController::class, 'index'])->name('index');
            Route::get('/export', [DocumentRequestController::class, 'export'])->name('export');
            Route::get('/statistics', [DocumentRequestController::class, 'statistics'])->name('statistics');
            Route::get('/document-types', [DocumentRequestController::class, 'documentTypes'])->name('document-types');
            Route::post('/document-types', [DocumentRequestController::class, 'storeDocumentType'])->name('document-types.store');
            Route::put('/document-types/{id}', [DocumentRequestController::class, 'updateDocumentType'])->name('document-types.update');
            Route::delete('/document-types/{id}', [DocumentRequestController::class, 'destroyDocumentType'])->name('document-types.destroy');
            Route::post('/bulk-mark-ready', [DocumentRequestController::class, 'bulkMarkReady'])->name('bulk-mark-ready');
            Route::post('/quick-return', [DocumentRequestController::class, 'quickReturn'])->name('quick-return');
            Route::get('/{id}', [DocumentRequestController::class, 'show'])->name('show');
            Route::post('/{id}/mark-ready', [DocumentRequestController::class, 'markReady'])->name('mark-ready');
            Route::post('/{id}/mark-picked', [DocumentRequestController::class, 'markPicked'])->name('mark-picked');
            Route::post('/{id}/mark-completed', [DocumentRequestController::class, 'markCompleted'])->name('mark-completed');
            Route::post('/{id}/approve-extension', [DocumentRequestController::class, 'approveExtension'])->name('approve-extension');
            Route::post('/{id}/reject-extension', [DocumentRequestController::class, 'rejectExtension'])->name('reject-extension');
            Route::get('/{id}/decharge', [DocumentRequestController::class, 'generateDecharge'])->name('decharge');
            Route::get('/{id}/attestation', [DocumentRequestController::class, 'generateAttestation'])->name('attestation');
            Route::delete('/{id}', [DocumentRequestController::class, 'destroy'])->name('destroy');
        });

        // Student Management
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentManagementController::class, 'index'])->name('index');
            Route::get('/export', [StudentManagementController::class, 'export'])->name('export');
            Route::post('/bulk-activate', [StudentManagementController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk-deactivate', [StudentManagementController::class, 'bulkDeactivate'])->name('bulk-deactivate');
            Route::get('/{id}', [StudentManagementController::class, 'show'])->name('show');
            Route::put('/{id}', [StudentManagementController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle-status', [StudentManagementController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{id}/reset-password', [StudentManagementController::class, 'resetPassword'])->name('reset-password');
            Route::post('/{id}/enroll-module', [StudentManagementController::class, 'enrollModule'])->name('enroll-module');
            Route::delete('/{id}/unenroll-module/{enrollmentId}', [StudentManagementController::class, 'unenrollModule'])->name('unenroll-module');
            Route::post('/{id}/enroll-program', [StudentManagementController::class, 'enrollProgram'])->name('enroll-program');
            Route::patch('/{id}/program-enrollment/{enrollmentId}', [StudentManagementController::class, 'updateProgramEnrollment'])->name('update-program-enrollment');
            Route::get('/{id}/convocation/{convocationId}', [StudentManagementController::class, 'downloadConvocation'])->name('download-convocation');
        });

        // Grade Management
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/', [GradeManagementController::class, 'index'])->name('index');
            Route::post('/module/{moduleId}/publish', [GradeManagementController::class, 'publishModule'])->name('publish-module');
            Route::post('/module/{moduleId}/unpublish', [GradeManagementController::class, 'unpublishModule'])->name('unpublish-module');
            Route::post('/bulk-publish', [GradeManagementController::class, 'bulkPublish'])->name('bulk-publish');

            // Import (bulk upload - all filieres & semesters at once)
            Route::get('/import', [GradeManagementController::class, 'showBulkImportForm'])->name('import');
            Route::get('/import/session-grades-template', [GradeManagementController::class, 'downloadBulkSessionGradesTemplate'])->name('import.session-grades-template');
            Route::post('/import/session-grades', [GradeManagementController::class, 'importBulkSessionGrades'])->name('import.session-grades');
            Route::get('/import/final-grades-template', [GradeManagementController::class, 'downloadFinalGradesTemplate'])->name('import.final-grades-template');
            Route::post('/import/final-grades', [GradeManagementController::class, 'importFinalGrades'])->name('import.final-grades');

            // Import Errors
            Route::get('/import-errors/{batchId}', [GradeManagementController::class, 'viewImportErrors'])->name('import-errors');
            Route::get('/import-errors/{batchId}/download', [GradeManagementController::class, 'downloadImportErrors'])->name('import-errors.download');

            // PV (Procès-Verbal) for modules
            Route::post('/module-pv', [GradeManagementController::class, 'downloadModulePV'])->name('module-pv');

            // Toggle reclamations for modules
            Route::post('/module/{moduleId}/toggle-reclamations', [GradeManagementController::class, 'toggleModuleReclamations'])->name('toggle-reclamations');
            Route::post('/bulk-toggle-reclamations', [GradeManagementController::class, 'bulkToggleReclamations'])->name('bulk-toggle-reclamations');

            // Reclamations
            Route::get('/reclamations', [GradeManagementController::class, 'reclamations'])->name('reclamations');
            Route::get('/reclamations/export', [GradeManagementController::class, 'exportReclamations'])->name('reclamations.export');
            Route::get('/reclamations/template', [GradeManagementController::class, 'downloadReclamationsTemplate'])->name('reclamations.template');
            Route::post('/reclamations/import', [GradeManagementController::class, 'importReclamations'])->name('reclamations.import');
            Route::get('/reclamations/pv', [GradeManagementController::class, 'downloadReclamationsPV'])->name('reclamations.pv');
            Route::get('/reclamations/{id}', [GradeManagementController::class, 'showReclamation'])->name('reclamations.show');
            Route::post('/reclamations/{id}/review', [GradeManagementController::class, 'reviewReclamation'])->name('reclamations.review');

            // Rattrapage Convocations
            Route::prefix('rattrapage')->name('rattrapage.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'index'])->name('index');
                
                // Justify absences
                Route::post('/justify/{gradeId}', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'justifyAbsence'])->name('justify');
                Route::post('/unjustify/{gradeId}', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'unjustifyAbsence'])->name('unjustify');
                Route::post('/bulk-justify', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'bulkJustifyAbsences'])->name('bulk-justify');
                Route::get('/justification-template', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'downloadJustificationTemplate'])->name('justification-template');
                
                // Get students for AJAX
                Route::get('/students', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'getStudents'])->name('students');
                
                // Convocations
                Route::get('/convocations', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'convocations'])->name('convocations');
                Route::post('/convocate/{examId}', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'convocateToRattrapage'])->name('convocate');
                Route::post('/bulk-convocate', [\App\Http\Controllers\Admin\RattrapageConvocationController::class, 'bulkConvocateByModule'])->name('bulk-convocate');
            });
            Route::post('/reclamations/{id}/resolve', [GradeManagementController::class, 'resolveReclamation'])->name('reclamations.resolve');
            Route::post('/reclamations/{id}/reject', [GradeManagementController::class, 'rejectReclamation'])->name('reclamations.reject');
            Route::post('/reclamations/bulk-update', [GradeManagementController::class, 'bulkUpdateReclamations'])->name('reclamations.bulk-update');

            // Reclamation Settings
            Route::get('/reclamation-settings', [GradeManagementController::class, 'reclamationSettings'])->name('reclamation-settings');
            Route::post('/reclamation-settings', [GradeManagementController::class, 'storeReclamationSetting'])->name('reclamation-settings.store');
            Route::put('/reclamation-settings/{id}', [GradeManagementController::class, 'updateReclamationSetting'])->name('reclamation-settings.update');
            Route::delete('/reclamation-settings/{id}', [GradeManagementController::class, 'deleteReclamationSetting'])->name('reclamation-settings.delete');
            Route::get('/modules-by-filiere', [GradeManagementController::class, 'getModulesByFiliereSemester'])->name('modules-by-filiere');
        });

        // Academic Management (Departments, Filieres, Modules, Professors)
        Route::prefix('academic')->name('academic.')->group(function () {
            // Dashboard
            Route::get('/', [AcademicManagementController::class, 'index'])->name('index');

            // Departments
            Route::get('/departments', [AcademicManagementController::class, 'departments'])->name('departments');
            Route::post('/departments', [AcademicManagementController::class, 'storeDepartment'])->name('departments.store');
            Route::put('/departments/{id}', [AcademicManagementController::class, 'updateDepartment'])->name('departments.update');
            Route::delete('/departments/{id}', [AcademicManagementController::class, 'destroyDepartment'])->name('departments.destroy');
            Route::get('/departments/export', [AcademicManagementController::class, 'exportDepartments'])->name('departments.export');
            Route::get('/departments/template', [AcademicManagementController::class, 'downloadDepartmentsTemplate'])->name('departments.template');
            Route::post('/departments/import', [AcademicManagementController::class, 'importDepartments'])->name('departments.import');

            // Filieres
            Route::get('/filieres', [AcademicManagementController::class, 'filieres'])->name('filieres');
            Route::post('/filieres', [AcademicManagementController::class, 'storeFiliere'])->name('filieres.store');
            Route::put('/filieres/{id}', [AcademicManagementController::class, 'updateFiliere'])->name('filieres.update');
            Route::delete('/filieres/{id}', [AcademicManagementController::class, 'destroyFiliere'])->name('filieres.destroy');
            Route::get('/filieres/export', [AcademicManagementController::class, 'exportFilieres'])->name('filieres.export');
            Route::get('/filieres/template', [AcademicManagementController::class, 'downloadFilieresTemplate'])->name('filieres.template');
            Route::post('/filieres/import', [AcademicManagementController::class, 'importFilieres'])->name('filieres.import');

            // Modules
            Route::get('/modules', [ModuleProfessorManagementController::class, 'modules'])->name('modules');
            Route::post('/modules', [ModuleProfessorManagementController::class, 'storeModule'])->name('modules.store');
            Route::put('/modules/{id}', [ModuleProfessorManagementController::class, 'updateModule'])->name('modules.update');
            Route::delete('/modules/{id}', [ModuleProfessorManagementController::class, 'destroyModule'])->name('modules.destroy');
            Route::get('/modules/export', [ModuleProfessorManagementController::class, 'exportModules'])->name('modules.export');
            Route::get('/modules/template', [ModuleProfessorManagementController::class, 'downloadModulesTemplate'])->name('modules.template');
            Route::post('/modules/import', [ModuleProfessorManagementController::class, 'importModules'])->name('modules.import');

            // Professors
            Route::get('/professors', [ModuleProfessorManagementController::class, 'professors'])->name('professors');
            Route::post('/professors', [ModuleProfessorManagementController::class, 'storeProfessor'])->name('professors.store');
            Route::put('/professors/{id}', [ModuleProfessorManagementController::class, 'updateProfessor'])->name('professors.update');
            Route::delete('/professors/{id}', [ModuleProfessorManagementController::class, 'destroyProfessor'])->name('professors.destroy');
            Route::get('/professors/export', [ModuleProfessorManagementController::class, 'exportProfessors'])->name('professors.export');
            Route::get('/professors/template', [ModuleProfessorManagementController::class, 'downloadProfessorsTemplate'])->name('professors.template');
            Route::post('/professors/import', [ModuleProfessorManagementController::class, 'importProfessors'])->name('professors.import');
        });
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
