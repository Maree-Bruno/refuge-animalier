<?php

namespace App\Http\Controllers;

use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            ->paginate(10)
            ->withQueryString();

        $species = Specie::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $races = Race::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $coats = Coat::when($querySearch,
            static fn($q) => $q
                ->where('name', 'like', "%$querySearch%"))
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $suitableTypes = SuitableType::when($querySearch,
            static fn($q) => $q->where('name', 'like', "%$querySearch%"))
            ->paginate(10)
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_type' => 'required|in:vaccine,specie,race,coat,suitable_type',
            'name' => 'required|string|max:255',
            'key' => 'required_if:model_type,suitable_type|nullable|string|max:50|unique:suitable_types,key',
            'specie_id' => 'required_if:model_type,vaccine,race|nullable|exists:species,id'
        ], [
            'key.unique' => 'Cette clé existe déjà. Veuillez en choisir une autre.',
            'key.required_if' => 'La clé est obligatoire pour ce type de ressource.',
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
                    'name' => $validated['name'],
                    'key' => $validated['key']
                ]);
                break;
        }

        return back();
    }

    public function destroy($id)
    {

    }
}
