<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Enums\AnimalStatus;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\ContactMessage;
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
        $adoptedAnimals = Animal::where('status', AnimalStatus::ADOPTED)->count();

        $refugedAnimals = Animal::whereIn('status', [
            AnimalStatus::IN_PROGRESS,
            AnimalStatus::VALIDATED
        ])->count();

        $accepted = AdoptionRequest::where('status', 'accepted')->count();

        $inProgress = AdoptionRequest::whereIn('status', ['pending', 'submitted'])->count();

        $animals = $this->filterAndPaginate(
            Animal::class,
            $request,
            ['coat', 'race', 'specie', 'vaccines', 'suitableTypes'],
            'animal_search',
        );
        $animals->through(fn($animal) => $animal->loadMissing([
            'suitableTypes', 'vaccines', 'notes' => fn($q) => $q->latest()
        ]));

        $adoptionRequestsQuery = AdoptionRequest::with([
            'adopter',
            'animal',
            'animal.coat',
            'animal.race',
            'animal.specie',
            'animal.vaccines',
            'animal.suitableTypes',
            'user'
        ]);

        if ($request->filled('status') && $request->status !== 'all') {
            $adoptionRequestsQuery->where('status', $request->status);
        }

        if ($request->filled('request_search')) {
            $search = $request->request_search;
            $adoptionRequestsQuery->whereHas('adopter', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('animal', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('orderby')) {
            $direction = $request->filled('dir') ? $request->dir : 'asc';

            if ($request->orderby === 'name') {
                $adoptionRequestsQuery->join('adopters', 'adoption_requests.adopter_id', '=', 'adopters.id')
                    ->orderBy('adopters.name', $direction)
                    ->select('adoption_requests.*');
            } elseif ($request->orderby === 'animal') {
                $adoptionRequestsQuery->join('animals', 'adoption_requests.animal_id', '=', 'animals.id')
                    ->orderBy('animals.name', $direction)
                    ->select('adoption_requests.*');
            } else {
                $adoptionRequestsQuery->orderBy($request->orderby, $direction);
            }
        } else {
            $adoptionRequestsQuery->orderBy('created_at', 'desc');
        }

        $adoptionRequests = $adoptionRequestsQuery->paginate(10)->withQueryString();


        $contactMessagesquery = ContactMessage::query();

        if ($request->filled('message_search')) {
            $search = $request->message_search;
            $contactMessagesquery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('subject', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%");
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $contactMessagesquery->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $contactMessagesquery->where('status', $request->status);
        }

        if ($request->filled('orderby')) {
            $direction = $request->input('dir', 'asc');
            $orderBy = $request->orderby;

            $contactMessagesquery->orderBy($orderBy, $direction);
        } else {
            $contactMessagesquery->latest('send_date');
        }

        $messages = $contactMessagesquery->paginate(10)->withQueryString();

        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
            'adoptionRequests' => $adoptionRequests,
            'animals' => $animals,
            'species' => $species,
            'allSuitableTypes' => $suitableTypes,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'refugedAnimals' => $refugedAnimals,
            'adoptedAnimals' => $adoptedAnimals,
            'accepted' => $accepted,
            'inProgress' => $inProgress,
            'messages' => $messages,
            'filters' => $request->only([
                'animal_search', 'request_search', 'message_search', 'orderby', 'dir', 'status'
            ]),
            'can' => [
                'publish' => Auth::user()->can('publish', Animal::class),
                'view' => Auth::user()->can('view', ContactMessage::class),
            ]
        ]);
    }
}
