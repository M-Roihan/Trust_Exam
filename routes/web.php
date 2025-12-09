<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherQuestionController;
use App\Http\Controllers\TeacherExamController; // <-- 1. Tambahan Import Controller Baru

use Illuminate\Support\Facades\Route;

// === AUTHENTICATION ===
Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::get('/captcha/refresh', function () {
    return captcha_src('flat');
})->name('captcha.refresh');


// === ADMIN ROUTES ===
Route::middleware('admin.auth')->group(function () {
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');

    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/admin/users', 'index')->name('admin.users.index');
        Route::get('/admin/users/data', 'data')->name('admin.users.data');
        Route::get('/admin/users/create', 'create')->name('admin.users.create');
        Route::post('/admin/users', 'store')->name('admin.users.store');
        Route::get('/admin/users/{role}/{id}/edit', 'edit')->name('admin.users.edit');
        Route::put('/admin/users/{role}/{id}', 'update')->name('admin.users.update');
        Route::delete('/admin/users/{role}/{id}', 'destroy')->name('admin.users.destroy');
    });
});


// === GURU ROUTES ===
Route::middleware('teacher.auth')->group(function () {
    Route::view('/guru/dashboard', 'guru.dashboard')->name('teacher.dashboard');

    // --- FITUR 1: BANK SOAL (Question Sets) ---
    Route::controller(TeacherQuestionController::class)
        ->prefix('guru/questions')
        ->name('teacher.questions.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/builder', 'builder')->name('builder');
            Route::post('/', 'store')->name('store');
            Route::put('/{questionSet}', 'update')->name('update');
            Route::get('/{questionSet}/edit', 'builder')->name('edit');
            Route::delete('/{questionSet}', 'destroy')->name('destroy');
        });

    // --- FITUR 2: JADWAL UJIAN (Exams) [BARU] ---
    // Ini route baru yang saya tambahkan untuk mengatur jadwal
    Route::controller(TeacherExamController::class)
        ->prefix('guru/exams')
        ->name('teacher.exams.')
        ->group(function () {
            Route::get('/', 'index')->name('index');      // List Jadwal
            Route::get('/create', 'create')->name('create'); // Form Buat Jadwal
            Route::post('/', 'store')->name('store');     // Simpan Jadwal
            Route::delete('/{id}', 'destroy')->name('destroy'); // Hapus Jadwal
        });
});


// === SISWA ROUTES ===
Route::middleware('student.auth')->group(function () {
    Route::get('/siswa/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});