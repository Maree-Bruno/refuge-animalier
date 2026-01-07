<?php

namespace App\Http\Controllers;

use App\Enums\AnimalStatus;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;

class PublicAnimalController extends Controller
{
    public function index()
    {
        $query = Animal::where('published', 1)
            ->whereIn('status', [
                AnimalStatus::VALIDATED,
            ])
            ->with(['specie', 'race', 'coat']);

        if (request()->has('gender') && request('gender') !== 'none') {
            $query->where('sex', request('gender'));
        }
        if (request()->has('specie_id') && request('specie_id') !== 'none') {
            $query->whereHas('race', function ($q) {
                $q->where('specie_id', request('specie_id'));
            });
        }
        if (request()->has('race_id') && request('race_id') !== 'none') {
            $query->where('race_id', request('race_id'));
        }
        if (request()->has('coat_id') && request('coat_id') !== 'none') {
            $query->where('coat_id', request('coat_id'));
        }
        if (request()->has('search') && request('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }

        $animals = $query->get();
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();

        return view('client.animals', compact('animals', 'species', 'races', 'coats'));
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
