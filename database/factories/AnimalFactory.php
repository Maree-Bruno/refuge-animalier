<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Coat;
use App\Models\Note;
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
            'name' => $this->faker->name(),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'chip' => '250 26 12 ' . $this->faker->numerify('########'),
            'age' => json_encode($this->faker->numberBetween(0, 20)),
            'description' => json_encode($this->faker->text()),
            'status' => $this->faker->randomElement(['Validated', 'In progress', 'Adopted']),
            'suitable' => $this->faker->randomElement(['Dog', 'Cat', 'Child', 'Baby']),
            'outside' => $this->faker->boolean(),
            'pictures' => 'test.jpg',
            'published' => $this->faker->boolean(),
            'admission_date' => $this->faker->date(),
            'coat_id' => Coat::factory(),
            'note_id' => null,
            'specie_id' => Specie::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
