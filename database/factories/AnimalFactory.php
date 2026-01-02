<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Coat;
use App\Models\Note;
use App\Models\Race;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'chip' => '250 26 12 ' . $this->faker->numerify('########'),
            'age' => $this->faker->numberBetween(0, 13),
            'description' => $this->faker->realText(),
            'status' => $this->faker->randomElement(['Validated', 'In progress', 'Adopted']),
            'outside' => $this->faker->boolean(),
            'pictures' => null,
            'published' => $this->faker->boolean(),
            'admission_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
