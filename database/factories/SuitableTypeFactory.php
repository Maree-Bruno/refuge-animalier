<?php

namespace Database\Factories;

use App\Models\SuitableType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SuitableTypeFactory extends Factory
{
    protected $model = SuitableType::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->word(),
            'name' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
