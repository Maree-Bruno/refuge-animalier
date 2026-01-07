<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2020, 2025),
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->endOfMonth(),
            'adopted_animals' => $this->faker->numberBetween(0, 50),
            'refuged_animals' => $this->faker->numberBetween(0, 100),
            'accepted_requests' => $this->faker->numberBetween(0, 30),
            'in_progress_requests' => $this->faker->numberBetween(0, 20),
            'animals_by_status' => [
                'Validated' => $this->faker->numberBetween(0, 20),
                'In progress' => $this->faker->numberBetween(0, 15),
                'Adopted' => $this->faker->numberBetween(0, 10),
            ],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
