<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $orderby = $request->get('orderby', 'name');
        $dir = $request->get('dir', 'asc');

        $volunteers = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy($orderby, $dir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('VolunteersIndexView', [
            'title' => "Bénévoles",
            'volunteers' => $volunteers,
            'filters' => [
                'search' => $search,
                'orderby' => $orderby,
                'dir' => $dir,
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
