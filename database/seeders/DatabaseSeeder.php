<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        Race::factory(5)->create();

        Specie::factory(20)
            ->for(Race::factory())
            ->create();

        Coat::factory(20)->create();

        // Create animals and assign them to the user
        Animal::factory(20)
            ->for($user) // Use 'for' instead of 'hasAttached'
            ->create([
                'specie_id' => Specie::inRandomOrder()->first()->id,
                'coat_id' => Coat::inRandomOrder()->first()->id,
            ]);
    }
}
