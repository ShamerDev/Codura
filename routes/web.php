<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

// Role-based dashboard redirect
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Use Spatie Permission package's hasRole method
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::view('profile', 'profile')->name('profile');

    // User-only routes
    Route::middleware('role:user')->group(function () {
        Route::view('user/dashboard', 'user.dashboard')->name('user.dashboard');
        Route::view('user/addentry', 'user.addentry')->name('user.addentry');
        Route::view('user/viewentry', 'user.viewentry')->name('user.viewentry');
        Route::view('user/entrydetails', 'user.entrydetails')->name('user.entrydetails');
        Route::view('user/manageentries', 'user.manageentries')->name('user.manageentries');
        Route::view('portfolio/profile', 'portfolio.profile')->name('portfolio.profile');
    });

    // Admin-only routes (future)
    Route::middleware('role:admin')->group(function () {
        Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    });
});

// Public route
Route::view('portfolio/viewpublic', 'portfolio.viewpublic')->name('portfolio.viewpublic');

require __DIR__ . '/auth.php';
