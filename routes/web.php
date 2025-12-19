<?php

use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PublicAnimalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VolunteerController;
use App\Models\Animal;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::domain('happypaws.test')->group(function () {
    Route::get('/', function () {
       $animals = Animal::where('published', 1)->get();
        return view('client/homepage', ['animals' => $animals]);
    })->name('homepage');
    Route::get('/happypaws', function () {
        return view('client/about');
    })->name('about');
    Route::get('/animals', [PublicAnimalController::class, 'index'])->name('animals');
    Route::get('/animals/{animal}', [PublicAnimalController::class, 'show'])->name('animals_show');
    Route::get('/contact', function () {
        return view('client/contact');
    })->name('contact');
    Route::get('/volunteer', function () {
        return view('client/volunteer');
    })->name('volunteer');
});
Route::domain('admin.happypaws.test')->group(function () {
    require __DIR__.'/settings.php';

    Route::get('/', function () {
        return Inertia::render('auth/Login', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    })->middleware(['guest', 'verified'])->name('home');

    Route::middleware(['auth', 'verified'])->group(function () {
        //dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //animals
        Route::get('/animals', [AnimalController::class, 'index'])->name('animals.index');
        Route::post('/animals', [AnimalController::class, 'store'])->name('animals.store');
        Route::patch('/animals/{animal}', [AnimalController::class, 'update'])->name('animals.update');
        Route::delete('/animals/{animal}', [AnimalController::class, 'destroy'])->name('animals.destroy');
        Route::delete('/animals/{animal}/images',
            [AnimalController::class, 'deleteImage'])->name('animals.deleteImage');

        //adoption request
        Route::get('/adoption', [AdoptionRequestController::class, 'index'])->name('adoption_requests.index');

        //notes
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');

        Route::middleware('role:admin')->group(function () {
            //reports
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

            //db
            Route::get('/database', [DatabaseController::class, 'index'])->name('database.index');

            //email
            Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');

            //volunteer
            Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
        });

    });
});

