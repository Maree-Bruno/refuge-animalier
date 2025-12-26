<?php

namespace Database\Factories;

use App\Models\Adopter;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AdoptionRequestFactory extends Factory
{
    protected $model = AdoptionRequest::class;

    public function definition(): array
    {
        return [
            'message' => $this->faker->word(),
            'request_date' => Carbon::now(),
            'adoption_date' => Carbon::now(),
            'status' => $this->faker->randomElement(['submitted','pending', 'accepted', 'rejected']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'adopter_id' => Adopter::factory(),
            'animal_id' => Animal::factory(),
            'user_id' => User::factory(),
        ];
    }
}
