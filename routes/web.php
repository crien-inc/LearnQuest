<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BadgeController as AdminBadgeController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Welcome page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // Lessons
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])
        ->name('courses.lessons.show');
    Route::post('/courses/{course}/lessons/{lesson}/progress', [LessonController::class, 'updateProgress'])
        ->name('courses.lessons.progress');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'complete'])
        ->name('courses.lessons.complete');
    Route::post('/courses/{course}/lessons/{lesson}/submit', [LessonController::class, 'submitAssignment'])
        ->name('courses.lessons.submit');

    // Badges
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Courses Management
    Route::resource('courses', AdminCourseController::class);

    // Lessons Management (nested under courses)
    Route::get('courses/{course}/lessons', [AdminLessonController::class, 'index'])
        ->name('courses.lessons.index');
    Route::get('courses/{course}/lessons/create', [AdminLessonController::class, 'create'])
        ->name('courses.lessons.create');
    Route::post('courses/{course}/lessons', [AdminLessonController::class, 'store'])
        ->name('courses.lessons.store');
    Route::get('courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'show'])
        ->name('courses.lessons.show');
    Route::get('courses/{course}/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])
        ->name('courses.lessons.edit');
    Route::put('courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'update'])
        ->name('courses.lessons.update');
    Route::delete('courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'destroy'])
        ->name('courses.lessons.destroy');
    Route::post('courses/{course}/lessons/reorder', [AdminLessonController::class, 'reorder'])
        ->name('courses.lessons.reorder');

    // Badges Management
    Route::resource('badges', AdminBadgeController::class);

    // Users Management
    Route::resource('users', AdminUserController::class);
});

require __DIR__.'/auth.php';
