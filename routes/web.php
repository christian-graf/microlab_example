<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/* Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar/reminder', [CalendarController::class, 'addReminder'])->name('calendar.reminder.add');
    Route::patch('/calendar/reminder/{id}', [CalendarController::class, 'updateReminder'])
        ->where('id', '[0-9]+')
        ->name('calendar.reminder.update');
    Route::delete('/calendar/reminder/{id}', [CalendarController::class, 'deleteReminder'])
        ->where('id', '[0-9]+')
        ->name('calendar.reminder.delete')
    ;
});

require __DIR__.'/auth.php';
