<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $species = Specie::all();
        $races = Race::all();
        $coats = Coat::all();
        $vaccines = Vaccine::all();
        $suitableTypes = SuitableType::all();

        $reports = Report::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->year . '-' . str_pad($report->month, 2, '0', STR_PAD_LEFT),
                    'month' => $report->month,
                    'year' => $report->year,
                    'label' => $report->label,
                    'adopted_animals' => $report->adopted_animals,
                    'refuged_animals' => $report->refuged_animals,
                    'accepted_requests' => $report->accepted_requests,
                    'in_progress_requests' => $report->in_progress_requests,
                    'total_animals' => $report->total_animals,
                    'date' => $report->start_date,
                    'animals_by_status' => $report->animals_by_status
                ];
            });

        return Inertia::render('ReportsIndexView', [
            'title' => 'Rapports statistiques',
            'reports' => $reports,
            'species' => $species,
            'allSuitableTypes' => $suitableTypes,
            'races' => $races,
            'coats' => $coats,
            'vaccines' => $vaccines,
            'filters' => $request->only(['search', 'orderby', 'dir']),
        ]);
    }

    public function exportPdf($month, $year)
    {
        $report = Report::where('month', $month)
            ->where('year', $year)
            ->firstOrFail();

        $data = [
            'month' => $report->label,
            'adoptedAnimals' => $report->adopted_animals,
            'refugedAnimals' => $report->refuged_animals,
            'accepted' => $report->accepted_requests,
            'inProgress' => $report->in_progress_requests,
            'animalsByStatus' => $report->animals_by_status,
            'generatedAt' => now()->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm')
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);

        $filename = 'rapport_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
