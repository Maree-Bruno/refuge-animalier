<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::domain('happypaws.test')->group(function () {
  Route::get('/', function () {
        return view('client/homepage');
  })->name('homepage');
    Route::get('/happypaws', function () {
        return view('client/about');
    })->name('about');
    Route::get('/animals', function () {
        return view('client/animals');
    })->name('animals');
    Route::get('/contact', function () {
        return view('client/contact');
    })->name('contact');
    Route::get('/volunteer', function () {
        return view('client/volunteer');
    })->name('volunteer');
});
Route::domain('admin.happypaws.test')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    })->name('home');

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    require __DIR__.'/settings.php';
});

