<?php

namespace Database\Factories;

use App\Models\Adopter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AdopterFactory extends Factory
{
    protected $model = Adopter::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'city' => $this->faker->city(),
            'cp' => $this->faker->postcode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
