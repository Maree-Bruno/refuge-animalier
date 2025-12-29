<?php

use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PublicAnimalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Settings\AvailabilityController;
use App\Http\Controllers\VolunteerController;
use App\Http\Middleware\UserIsAdminMiddleware;
use App\Models\Animal;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::domain('happypaws.test')->group(function () {

    //homepage
    Route::get('/', function () {
        $animals = Animal::where('published', 1)->get();
        return view('client/homepage', ['animals' => $animals]);
    })->name('homepage');

    //about
    Route::get('/happypaws', function () {
        return view('client/about');
    })->name('about');

    //animals
    Route::get('/animals', [PublicAnimalController::class, 'index'])->name('animals');
    Route::get('/animals/{animal}', [PublicAnimalController::class, 'show'])->name('animals_show');
    Route::post('/animals/{animal}', [AdoptionRequestController::class, 'store'])->name('adoption_requests.public.store');

    //contact
    Route::get('/contact', function () {
        return view('client/contact');
    })->name('contact');
    Route::post('/contact', [ContactMessageController::class, 'submitContact'])->name('contact.submit');

    //volunteer
    Route::get('/volunteer', function () {
        return view('client/volunteer');
    })->name('volunteer');
    Route::post('/volunteer', [ContactMessageController::class, 'submitVolunteer'])->name('volunteer.submit');
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
        Route::post('/adoption/', [AdoptionRequestController::class, 'store'])->name('adoption_requests.store');
        Route::patch('/adoption/{adoption}',
            [AdoptionRequestController::class, 'update'])->name('adoption_requests.update');
        Route::delete('/adoption/{adoption}',
            [AdoptionRequestController::class, 'destroy'])->name('adoption_requests.destroy');

        //notes
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');


        Route::middleware(UserIsAdminMiddleware::class)->group(function () {
            //reports
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/{month}/{year}/export-pdf',
                [ReportController::class, 'exportPdf'])->name('reports.export-pdf');

            //db
            Route::get('/database', [DatabaseController::class, 'index'])->name('database.index');
            Route::post('/database', [DatabaseController::class, 'store'])->name('database.store');

            //contact
            Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact_message.index');
            Route::patch('/contact-messages/{id}/status', [ContactMessageController::class, 'update'])->name('contact_message.update');
            Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact_message.destroy');


            //volunteer
            Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
            Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');
            Route::patch('/volunteers/{volunteer}', [VolunteerController::class, 'update'])->name('volunteers.update');
            Route::delete('/volunteers/{volunteer}',
                [VolunteerController::class, 'destroy'])->name('volunteers.destroy');
            Route::delete('/volunteers/{volunteer}/image',
                [VolunteerController::class, 'deleteImage'])->name('volunteers.deleteImage');
        });

    });
});

