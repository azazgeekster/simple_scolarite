<?php

use App\Http\Controllers\Auth\AccountActivationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\Student\ConvocationController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\DemandeController;
use App\Http\Controllers\Student\GradesController;
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentReclamationController;
use App\Http\Controllers\Student\StudentSituationController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes                                                              |
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'student'], function () {
    // MIDDLEWARE GUEST
    Route::middleware('guest:student')->group(function () {
        Route::get('login', [StudentLoginController::class, 'showLoginForm'])->name('student.loginform');
        Route::post('login', [StudentLoginController::class, 'login'])->name('student.login');

        Route::get('activate', [AccountActivationController::class, 'showActivationForm'])->name('student.activate.form');
        Route::post('activate', [AccountActivationController::class, 'sendActivationEmail'])->name('student.activate.send');
        Route::get('activate/{token}', [AccountActivationController::class, 'showSetPasswordForm'])->name('student.activate.setpassword');
        Route::post('activate/{token}', [AccountActivationController::class, 'completeActivation'])->name('student.activate.complete');
    });

    // MIDDLEWARE STUDENT
    Route::middleware(['auth:student', 'verified'])->group(function () {
        // LOGIN things
        Route::get('dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        // Route::get('dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        Route::post('logout', [StudentLoginController::class, 'logout'])->name('student.logout');

        // PROFILE
        Route::get("/edit_profile.jsp", [StudentProfileController::class, 'edit'])->name("student.profile.edit");
        Route::get("/show_profile.jsp", [StudentProfileController::class, 'show'])->name("student.profile.show");
        Route::post("/show_profile.jsp", [StudentProfileController::class, 'update'])->name("student.profile.update");
        // PROFILE PHOTO SUBMITTING
        Route::post('/profile/update-photo', [StudentProfileController::class, 'updatePhoto'])->name('profile.update.photo');
        ///profile  photo delete
        Route::delete('/photo', [StudentProfileController::class, 'deletePhoto'])->name('studentprofile.delete.photo');

        // Grades
        Route::get('/grades', [GradesController::class, 'index'])
            ->name('student.grades');


        //exams
        Route::get('exams', [StudentExamController::class, 'index'])
            ->name('exams.index');
        Route::get('exams/{exam}', [StudentExamController::class, 'show'])
            ->name('exams.show');
        Route::get('exams/{exam}/convocation', [StudentExamController::class, 'convocation'])
            ->name('exams.convocation');

        Route::get('/convocations', [App\Http\Controllers\Student\ConvocationController::class, 'index'])
            ->name('student.convocations');

        Route::get('/convocations/history', [App\Http\Controllers\Student\ConvocationController::class, 'history'])
            ->name('student.convocations.history');

        Route::get('/convocations/download', [App\Http\Controllers\Student\ConvocationController::class, 'download'])
            ->name('student.convocations.download');

            /// RECLAMMATIONS
        Route::get('reclamations', [StudentReclamationController::class, 'index'])
            ->name('reclamations.index');
        Route::get('reclamations/create/{moduleGrade}', [StudentReclamationController::class, 'create'])
            ->name('reclamations.create');
        Route::post('reclamations/{moduleGrade}', [StudentReclamationController::class, 'store'])
            ->name('reclamations.store');
        Route::get('reclamations/{reclamation}', [StudentReclamationController::class, 'show'])
            ->name('reclamations.show');
        Route::delete('reclamations/{reclamation}', [StudentReclamationController::class, 'destroy'])
            ->name('reclamations.destroy');
        // DOCUMENTS REQUESTS:
        Route::get('/document/request', [DemandeController::class, 'index'])->name('student.demande.index');
        Route::post('/document/retrait', [DemandeController::class, 'store'])->name('student.demande.store');

        Route::delete('/document/{demande}/cancel', [DemandeController::class, 'cancel'])->name('student.demande.cancel');
        Route::get('/document/{demande}/print', [DemandeController::class, 'print'])->name('student.demande.print');

        Route::post('/document/{demande}/extension', [DemandeController::class, 'requestExtension'])
            ->name('student.demande.extension');

        /// releve
        Route::get('/releve', [DemandeController::class, 'index_releve'])->name('student.releve.index');
        Route::post('/releve', [DemandeController::class, 'store_releve'])->name('student.releve.store');

        Route::get('/ma-situation', [StudentSituationController::class, 'mySituation'])
            ->name('student.mysituation');

        Route::get('/mon-historique', [StudentSituationController::class, 'history'])
            ->name('student.history');

        Route::get('/releve-notes/download', [StudentSituationController::class, 'downloadTranscript'])
            ->name('student.transcript.download');
    });
    Route::view('logout', "auth.logoutpage")->name('student.logoutpage');
});


//for google

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');

// Route to handle the callback from Google
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');


/// forget and reset password
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');


Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');











// Route::group(['prefix' => 'student'], function () {
//   Route::middleware('guest:student')->group(function () {
//       Route::get('login', [StudentLoginController::class, 'showLoginForm'])->name('student.loginform');
//       Route::post('login', [StudentLoginController::class, 'login'])->name('student.login');
//   });

//   Route::middleware('auth:student')->group(function () {
//       Route::get('dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
//       Route::get('/', function () {
//           return redirect()->route('student.dashboard');  // Redirect /student to dashboard
//       });
//       Route::post('logout', [StudentLoginController::class, 'logout'])->name('student.logout');
//   });
// });

// /// for I don't have account

// Route::get('activate', [AccountActivationController::class, 'showActivationForm'])->name('student.activate.form');
// Route::post('activate', [AccountActivationController::class, 'activateRequest'])->name('student.activate.request');
// Route::get('activate/{token}', [AccountActivationController::class, 'activateAccount'])->name('student.activate');
