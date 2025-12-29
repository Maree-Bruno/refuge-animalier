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

        $suitableTypes = SuitableType::when($querySearch, static fn($q) => $q->where('name', 'like', "%$querySearch%"))
            ->paginate(10)->withQueryString();

        return Inertia::render('DatabaseIndexView', [
            'title' => 'Base de données',
            'filters' => $request->only(['database_search', 'orderby', 'dir']),
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
