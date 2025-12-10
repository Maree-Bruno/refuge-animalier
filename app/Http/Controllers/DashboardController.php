<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = request('orderby', 'name');
        $dir = request('dir', 'asc');
        $search = request('search', '');
        $query = Animal::query();
        $animals = $query
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('chip', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy($orderBy, $dir)
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
            'animals' => $animals,
            'filters' => [
                'search' => $search
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
