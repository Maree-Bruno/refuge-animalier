<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Enums\UserRole;
use App\Mail\AdoptionRequestReceivedMail;
use App\Mail\AdoptionRequestStatusUpdatedMail;
use App\Models\Adopter;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use App\Models\User;
use App\Notifications\AdoptionRequestCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class AdoptionRequestController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $query = AdoptionRequest::with([
            'adopter',
            'animal',
            'animal.coat',
            'animal.race',
            'animal.specie',
            'animal.vaccines',
            'animal.suitableTypes',
            'user'
        ]);

        if ($request->filled('request_search')) {
            $search = $request->request_search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('adopter', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                    ->orWhereHas('animal', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('orderby')) {
            $direction = $request->input('dir', 'asc');
            $orderBy = $request->orderby;

            if (in_array($orderBy, ['name', 'email', 'phone'])) {
                $query->join('adopters', 'adoption_requests.adopter_id', '=', 'adopters.id')
                    ->select('adoption_requests.*')
                    ->orderBy("adopters.{$orderBy}", $direction);
            } elseif ($orderBy === 'animal') {
                $query->orderBy(
                    \App\Models\Animal::select('name')
                        ->whereColumn('animals.id', 'adoption_requests.animal_id'),
                    $direction
                );
            } else {
                $query->orderBy($orderBy, $direction);
            }
        } else {
            $query->latest();
        }

        $adoptionRequests = $query->paginate(10)->withQueryString();

        $animals = Animal::with(['coat', 'race', 'specie', 'vaccines', 'suitableTypes'])->get();
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();
        $vaccines = Vaccine::all();
        $suitableTypes = SuitableType::all();

        return Inertia::render('AdoptionRequestsIndexView', [
            'title' => "Demandes d'adoption",
            'adoptionRequests' => $adoptionRequests,
            'filters' => $request->only(['request_search', 'orderby', 'dir', 'status']),
            'animals' => $animals,
            'species' => $species,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'suitableTypes' => $suitableTypes,
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
            'status' => 'in:accepted,rejected,submitted,pending',
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

        $adoptionRequest = AdoptionRequest::create([
            'adopter_id' => $adopter->id,
            'animal_id' => $validated['animal_id'],
            'message' => $validated['message'],
            'request_date' => now(),
            'adoption_date' => null,
            'status' => $validated['status'] ?? 'submitted',
            'user_id' => auth()->id(),
        ]);

        $adoptionRequest->load(['adopter', 'animal']);

        Mail::to($adopter->email)->send(new AdoptionRequestReceivedMail($adoptionRequest));

        $users = User::all();

        Notification::send($users->where('status', UserRole::ADMIN), new AdoptionRequestCreatedNotification($adoptionRequest));

        return back();
    }

    public function update(Request $request, $id)
    {
        $adoptionRequest = AdoptionRequest::with(['adopter', 'animal'])->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:submitted,pending,accepted,rejected',
        ]);

        $statusChanged = $adoptionRequest->status !== $validated['status'];

        $adoptionRequest->update([
            'status' => $validated['status'],
            'adoption_date' => $validated['status'] === 'accepted' ? now() : null,
        ]);

        if ($statusChanged && $validated['status'] !== 'submitted') {
            Mail::to($adoptionRequest->adopter->email)
                ->send(new AdoptionRequestStatusUpdatedMail($adoptionRequest));
        }

        return back();
    }

    public function destroy($id)
    {
        $adoptionRequest = AdoptionRequest::findOrFail($id);
        $adoptionRequest->delete();

        return back();
    }
}
