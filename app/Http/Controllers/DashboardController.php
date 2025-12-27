<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use App\Policies\AnimalPolicy;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();
        $vaccines = Vaccine::all();
        $suitableTypes = SuitableType::all();
        $animals = $this->filterAndPaginate(Animal::class, $request,['coat', 'race', 'specie', 'vaccines', 'suitableTypes']);
        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
            'animals' => $animals,
            'species' => $species,
            'suitableTypes' => $suitableTypes,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'filters' => $request->only(['search', 'orderby', 'dir', 'status']),
            'can' => [
                'publish' => Auth::user()->can('publish', Animal::class),
            ]
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
