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
        $userAdmin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'phone' => '0495793947',

            'role'=>'admin',
            'password' => bcrypt('password')
        ]);
        $userVolunteer = User::factory()->create([
            'name' => 'Volunteer',
            'email' => 'test@test.com',
            'phone' => '0498283383',

            'role'=>'volunteer',
            'password' => bcrypt('password')
        ]);
        $userVolunteers = User::factory(20)->create();


        Specie::factory(5)
            ->has(Race::factory()->count(2))
            ->has(Vaccine::factory()->count(5))
            ->create();

        Coat::factory(20)->create();

    }
}
