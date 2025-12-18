<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DatabaseController extends Controller
{
    public function index()
    {
        $vaccines = Vaccine::all();
        return Inertia::render('DatabaseIndexView', ['vaccines'=>$vaccines]);
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
