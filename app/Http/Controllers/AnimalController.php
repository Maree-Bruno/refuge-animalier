<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimalController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();
        $animals = $this->filterAndPaginate(Animal::class, $request, ['coat', 'specie.race']);
        return Inertia::render('AnimalsIndexView', [
            'title' => 'Animals',
            'animals' => $animals,
            'species' => $species,
            'races' => $races,
            'coats' => $coats,
            'filters' => $request->only(['search', 'orderby', 'dir', 'status']),
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'sex' => 'required',
            'chip' => 'nullable',
            'age' => 'required',
            'description' => 'required',
            'pictures' => 'file:png,jpg,jpeg,webp |nullable',
            'suitable' => 'required',
            'outside' => 'boolean',
            'published' => 'boolean',
        ]);
        $species = $request['specie_id'];
        $coats = $request['coat_id'];
        $races = $request['race_id'];
        $animal = Animal::create($validated);
        if ($species) {
            foreach ($species as $specie) {
                $animal->specie()->attach($specie);
            }
        }
        if ($coats) {
            foreach ($coats as $coat) {
                $animal->coat()->attach($coat);
            }
        }
        if ($races) {
            foreach ($races as $race) {
                $animal->race()->attach($race);
            }
        }

        return back();
    }

    public function show(Animal $animal)
    {

    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
