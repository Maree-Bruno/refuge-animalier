<?php

namespace Database\Factories;

use App\Models\Race;
use App\Models\Specie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SpecieFactory extends Factory
{
    protected $model = Specie::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'race_id' => Race::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
