<?php

namespace App\Http\Controllers;

use App\Models\Adopter;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdoptionRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = AdoptionRequest::with(['adopter', 'animal', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('adopter', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                    ->orWhereHas('animal', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }


        if ($request->filled('orderby')) {
            $direction = $request->input('dir', 'asc');
            $query->orderBy($request->orderby, $direction);
        } else {
            $query->latest();
        }

        $adoptionRequests = $query->paginate(10)->withQueryString();

        return Inertia::render('AdoptionRequestsIndexView', [
            'title' => "Demandes d'adoption",
            'adoptionRequests' => $adoptionRequests,
            'filters' => $request->only(['search', 'orderby', 'dir', 'status']),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'cp' => 'nullable|string|max:10',
            'animal_id' => 'required|exists:animals,id',
            'message' => 'required|string|max:1000',
        ]);
        $adopter = Adopter::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'phone' => $validated['tel'],
                'address' => $validated['address'] ?? null,
                'number' => $validated['number'] ?? null,
                'city' => $validated['city'] ?? null,
                'cp' => $validated['cp'] ?? null,
            ]
        );


        AdoptionRequest::create([
            'adopter_id' => $adopter->id,
            'animal_id' => $validated['animal_id'],
            'message' => $validated['message'],
            'request_date' => now(),
            'adoption_date' => null,
            'status' => 'submitted',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', __('contact.request_sent'));
    }
    public function update(Request $request, $id)
    {
        $adoptionRequest = AdoptionRequest::with('adopter')->findOrFail($id);

        $validated = $request->validate([
            'adopter.name' => 'required|string|max:255',
            'adopter.email' => 'required|email|max:255',
            'adopter.phone' => 'required|string|max:20',
            'adopter.address' => 'nullable|string|max:255',
            'adopter.number' => 'nullable|string|max:10',
            'adopter.city' => 'nullable|string|max:100',
            'adopter.cp' => 'nullable|string|max:10',
            'animal_id' => 'required|exists:animals,id',
            'message' => 'required|string|max:1000',
            'status' => 'required|in:submitted,pending,accepted,rejected',
        ]);

        $adoptionRequest->update([
            'animal_id' => $validated['animal_id'],
            'message' => $validated['message'],
            'status' => $validated['status'],
            'adoption_date' => $validated['status'] === 'accepted' ? now() : null,
        ]);

        $adoptionRequest->adopter->update($validated['adopter']);

        return back();
    }

    public function destroy($id)
    {
        $adoptionRequest = AdoptionRequest::findOrFail($id);
        $adoptionRequest->delete();

        return redirect()->route('adoption-requests.index')
            ->with('success', 'Demande d\'adoption supprimée avec succès');
    }
}
