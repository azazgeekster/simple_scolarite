<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Student\StudentProfileController;
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


Route::get('/', function () {
    return redirect(route("student.login"));
});


Route::get('/student', function () {
    return redirect(route("student.login"));
});

// Route::view("/view", "ui");
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');


// Route::put('/photo', [StudentProfileController::class, 'updatePhoto'])->name('profile.update.photo');
// Forgot Password Routes

