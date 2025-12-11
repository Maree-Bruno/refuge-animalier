<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Models\Animal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $animals = $this->filterAndPaginate(Animal::class, $request, ['coat', 'specie.race']);
        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
            'animals' => $animals,
            'filters' => $request->only(['search', 'orderby', 'dir', 'status']),
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
