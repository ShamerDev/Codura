<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('main/test', 'test')
    ->middleware(['auth'])
    ->name('test');

Route::view('user/addentry', 'user.addentry')
    ->middleware(['auth'])
    ->name('user.addentry');

Route::view('user/viewentry', 'user.viewentry')
    ->middleware(['auth'])
    ->name('user.viewentry');

Route::view('user/entrydetails', 'user.entrydetails')
    ->middleware(['auth'])
    ->name('user.entrydetails');

Route::view('user/manageentries', 'user.manageentries')
    ->middleware(['auth'])
    ->name('user.manageentries');

require __DIR__ . '/auth.php';
