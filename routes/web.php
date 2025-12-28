<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HoursController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('events', EventController::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('hours', [HoursController::class, 'index'])->name('hours.index');
    Route::put('hours', [HoursController::class, 'update'])->name('hours.update');
});

require __DIR__.'/settings.php';
