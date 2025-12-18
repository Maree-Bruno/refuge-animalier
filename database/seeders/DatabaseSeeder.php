<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\User;
use App\Models\Vaccine;
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

        Specie::factory(5)
            ->has(Race::factory()->count(2))
            ->has(Vaccine::factory()->count(5))
            ->create();

        Coat::factory(20)->create();

        /*Animal::factory(20)
            ->for($user)
            ->create([
                'race_id' => Race::inRandomOrder()->first()->id,
                'coat_id' => Coat::inRandomOrder()->first()->id,
            ]);*/
    }
}
