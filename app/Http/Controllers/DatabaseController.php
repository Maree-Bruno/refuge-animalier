<?php

namespace App\Http\Controllers;

use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DatabaseController extends Controller
{
    public function index(Request $request)
    {
        $querySearch = $request->database_search;
        $status = $request->status ?? 'all';

        $vaccines = Vaccine::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(5)
            ->withQueryString();

        $species = Specie::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(5)
            ->withQueryString();

        $races = Race::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(5)
            ->withQueryString();

        $coats = Coat::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(5)
            ->withQueryString();

        $suitableTypes = SuitableType::when($querySearch,
            static fn($q) => $q->where('name', 'like', "%$querySearch%"))
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('DatabaseIndexView', [
            'title' => 'Base de données',
            'filters' => $request->only(['database_search', 'orderby', 'dir', 'status']),
            'vaccines' => $vaccines,
            'species' => $species,
            'races' => $races,
            'coats' => $coats,
            'suitableTypes' => $suitableTypes
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_type' => 'required|in:vaccine,specie,race,coat,suitable_type',
            'name' => 'required|string|max:255',
            'specie_id' => 'required_if:model_type,vaccine,race|nullable|exists:species,id'
        ]);

        switch ($validated['model_type']) {
            case 'vaccine':
                Vaccine::create([
                    'name' => $validated['name'],
                    'specie_id' => $validated['specie_id']
                ]);
                break;

            case 'specie':
                Specie::create([
                    'name' => $validated['name']
                ]);
                break;

            case 'race':
                Race::create([
                    'name' => $validated['name'],
                    'specie_id' => $validated['specie_id']
                ]);
                break;

            case 'coat':
                Coat::create([
                    'name' => $validated['name']
                ]);
                break;

            case 'suitable_type':
                SuitableType::create([
                    'name' => $validated['name']
                ]);
                break;
        }

        return redirect()->route('database.index');
    }

    public function show($id)
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
