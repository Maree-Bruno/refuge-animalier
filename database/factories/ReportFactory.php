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
            'month' => $this->faker->randomNumber(),
            'year' => $this->faker->randomNumber(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
            'adopted_animals' => $this->faker->randomNumber(),
            'refuged_animal' => $this->faker->randomNumber(),
            'accepted_requests' => $this->faker->randomNumber(),
            'in_progress_requests' => $this->faker->randomNumber(),
            'animals_by_status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
