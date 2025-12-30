<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Note;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $animals = Animal::select('id', 'name')->get();
        $adoptionRequests = AdoptionRequest::with([
            'adopter:id,name',
            'animal:id,name'
        ])->get();

      $query = Note::with([
            'notable' => function ($morph) {
                $morph->morphWith([
                   AdoptionRequest::class => ['adopter', 'animal'],
                ]);
            }
        ]);
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('content', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->has('type') && $request->type && $request->type !== 'all') {
            $query->where('notable_type', $request->type);
        }
        $orderBy = $request->get('orderby', 'created_at');
        $direction = $request->get('dir', 'desc');
        $query->orderBy($orderBy, $direction);

        $notes = $query->paginate(15)->withQueryString();

        return Inertia::render('NotesIndexView', [
            'title' => 'Notes',
            'notes' => $notes,
            'animals' => $animals,
            'adoptionRequests' => $adoptionRequests,
            'filters' => [
                'search' => $request->search,
                'type' => $request->type ?? 'all',
                'orderby' => $orderBy,
                'dir' => $direction,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notable_type' => 'required|string',
            'notable_id' => 'required|integer',
        ]);

        Note::create($validated);

        return back();
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $note->update($validated);

        return back();
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return back();
    }
}
