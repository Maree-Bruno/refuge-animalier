<?php

namespace App\Http\Controllers;

use App\Concerns\FilterablePaginate;
use App\Enums\AnimalStatus;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    use FilterablePaginate;

    public function index(Request $request)
    {
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();
        $vaccines = Vaccine::all();
        $suitableTypes = SuitableType::all();
        $reports = collect();
        for ($i = 0; $i < 36; $i++) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $adoptedCount = Animal::where('status', AnimalStatus::ADOPTED)
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count();

            $refugedCount = Animal::whereIn('status', [
                AnimalStatus::IN_PROGRESS,
                AnimalStatus::VALIDATED
            ])->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $acceptedCount = AdoptionRequest::where('status', 'accepted')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count();

            $inProgressCount = AdoptionRequest::whereIn('status', ['pending', 'submitted'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $animals = Animal::with(['suitableTypes', 'vaccines', 'race', 'specie'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('status')
                ->get()
                ->groupBy('status');

            $reports->push([
                'id' => $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT),
                'month' => $month,
                'year' => $year,
                'label' => $date->locale('fr')->isoFormat('MMMM YYYY'),
                'adopted_animals' => $adoptedCount,
                'refuged_animals' => $refugedCount,
                'accepted_requests' => $acceptedCount,
                'in_progress_requests' => $inProgressCount,
                'total_animals' => $adoptedCount + $refugedCount,
                'date' => $startDate,
                'animals_by_status' => $animals
            ]);
        }

        return Inertia::render('ReportsIndexView', [
            'title' => 'Rapports statistiques',
            'reports' => $reports,
            'species' => $species,
            'allSuitableTypes' => $suitableTypes,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'filters' => $request->only(['search', 'orderby', 'dir']),
            'can' => [
                'publish' => Auth::user()->can('publish', Animal::class),
            ]
        ]);
    }

    public function exportPdf($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $adoptedAnimals = Animal::where('status', AnimalStatus::ADOPTED)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        $refugedAnimals = Animal::whereIn('status', [
            AnimalStatus::IN_PROGRESS,
            AnimalStatus::VALIDATED
        ])->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $accepted = AdoptionRequest::where('status', 'accepted')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        $inProgress = AdoptionRequest::whereIn('status', ['pending', 'submitted'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $animals = Animal::with(['suitableTypes', 'vaccines', 'race', 'specie'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('status')
            ->get()
            ->groupBy('status');

        $data = [
            'month' => Carbon::create($year, $month, 1)->locale('fr')->isoFormat('MMMM YYYY'),
            'adoptedAnimals' => $adoptedAnimals,
            'refugedAnimals' => $refugedAnimals,
            'accepted' => $accepted,
            'inProgress' => $inProgress,
            'animalsByStatus' => $animals,
            'generatedAt' => now()->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm')
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);

        $filename = 'rapport_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
