<?php

namespace App\Console\Commands;

use App\Enums\AnimalStatus;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyReportsCommand extends Command
{
    protected $signature = 'reports:generate {--months=1 : Number of months to generate}';
    protected $description = 'Generate monthly reports';

    public function handle(): int
    {
        $months = $this->option('months');

        for ($i = 0; $i < $months; $i++) {
            $date = now()->subMonths($i);
            $this->generateReport($date->month, $date->year);
        }

        $this->info("Generated {$months} report(s) successfully!");
        return 0;
    }

    private function generateReport(int $month, int $year): void
    {
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

        Report::updateOrCreate(
            ['month' => $month, 'year' => $year],
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'adopted_animals' => $adoptedCount,
                'refuged_animals' => $refugedCount,
                'accepted_requests' => $acceptedCount,
                'in_progress_requests' => $inProgressCount,
                'animals_by_status' => $animals->toArray(),
            ]
        );

        $this->info("Report for {$month}/{$year} generated.");
    }
}
