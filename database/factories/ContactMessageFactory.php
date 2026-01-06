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
            'type' => $this->faker->randomElement([ContactMessage::TYPE_CONTACT, ContactMessage::TYPE_VOLUNTEER]),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'subject' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement([ContactMessage::STATUS_NEW, ContactMessage::STATUS_READ, ContactMessage::STATUS_ARCHIVED]),
            'send_date' => Carbon::now(),
            'address' => $this->faker->address(),
            'number' => $this->faker->buildingNumber(),
            'cp' => $this->faker->postcode(),
            'city' => $this->faker->city(),
        ];
    }
}
