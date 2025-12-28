<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'subject' => $this->faker->word(),
            'content' => $this->faker->word(),
            'status' => $this->faker->word(),
            'send_date' => $this->faker->word(),
            'address' => $this->faker->address(),
            'number' => $this->faker->word(),
            'cp' => $this->faker->word(),
            'city' => $this->faker->city(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
