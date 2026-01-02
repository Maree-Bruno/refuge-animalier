<?php

namespace App\Http\Controllers;

use App\Enums\AnimalStatus;
use App\Models\Animal;

class PublicAnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::where('published', 1)
            ->whereIn('status', [
                AnimalStatus::VALIDATED,
            ])
            ->get();

        return view('client.animals', compact('animals'));
    }

    public function show(Animal $animal)
    {
        $animal->load('suitableTypes');

        $otherAnimals = Animal::where('id', '!=', $animal->id)
            ->whereIn('status', [
                AnimalStatus::VALIDATED,
            ])
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('client.animals_show', compact('animal', 'otherAnimals'));
    }
}
