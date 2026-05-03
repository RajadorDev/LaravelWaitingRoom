<?php

use App\Http\Controllers\UserQueueController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified', 'limited_users'])->group(
    function () {
        Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/queue', [UserQueueController::class, 'loadQueuePage']);
    }
);

require __DIR__.'/settings.php';
