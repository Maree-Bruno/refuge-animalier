<?php

namespace Database\Seeders;

use App\Models\Adopter;
use App\Models\AdoptionRequest;
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
        // 1️⃣ Création des utilisateurs
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'phone' => '0495793947',
            'role' => 'admin',
            'password' => bcrypt('password')
        ]);

        $volunteer = User::factory()->create([
            'name' => 'Volunteer',
            'email' => 'test@test.com',
            'phone' => '0498283383',
            'role' => 'volunteer',
            'password' => bcrypt('password')
        ]);

        // 2️⃣ Création des pelages
        $coatNames = ['Blanc', 'Noir', 'Doré', 'Tâché', 'Tricolore', 'Brun'];
        $coats = collect();
        foreach ($coatNames as $name) {
            $coats->push(Coat::create(['name' => $name]));
        }

        // 3️⃣ Création des espèces avec races et vaccins
        $speciesData = [
            'Dog' => [
                'races' => ['Husky', 'Chihuahua', 'Golden Retriever', 'Berger Australien', 'Cocker'],
                'vaccines' => ['CHPPi', 'Rage', 'Toux du chenil']
            ],
            'Cat' => [
                'races' => ['Persan', 'Siamois', 'Maine Coon', 'Sphinx'],
                'vaccines' => ['Typhus', 'Coryza', 'Leucose féline']
            ]
        ];

        $species = collect();
        foreach ($speciesData as $specieName => $data) {
            $specie = Specie::create(['name' => $specieName]);
            $species->push($specie);

            // Création des races
            foreach ($data['races'] as $raceName) {
                Race::create([
                    'name' => $raceName,
                    'specie_id' => $specie->id,
                ]);
            }

            // Création des vaccins
            foreach ($data['vaccines'] as $vaccineName) {
                Vaccine::create([
                    'name' => $vaccineName,
                    'specie_id' => $specie->id,
                ]);
            }
        }

        // 4️⃣ Création des animaux
        $animals = collect();
        for ($i = 0; $i < 20; $i++) {
            // Choisir une race aléatoire existante
            $race = Race::inRandomOrder()->first();

            $animal = Animal::factory()->create([
                'race_id' => $race->id,
                'coat_id' => $coats->random()->id,
                'user_id' => $volunteer->id,
            ]);

            // Associer les vaccins compatibles avec l'espèce
            $compatibleVaccines = $race->specie->vaccines;
            if ($compatibleVaccines->count() > 0) {
                $animal->vaccines()->attach(
                    $compatibleVaccines->random(rand(0, $compatibleVaccines->count()))->pluck('id')->toArray()
                );
            }

            $animals->push($animal);
        }

        // 5️⃣ Création des adopters
        $adopters = Adopter::factory(10)->create();

        // 6️⃣ Création des demandes d'adoption
        for ($i = 0; $i < 10; $i++) {
            $animal = $animals->random();

            // Choisir un adopteur existant ou en créer un nouveau
            $adopter = $adopters->random();

            $existingAdopter = Adopter::firstOrCreate(
                ['email' => $adopter->email], // unique key
                [
                    'name' => $adopter->name,
                    'phone' => $adopter->phone,
                    'street' => $adopter->street,
                    'city' => $adopter->city,
                    'cp' => $adopter->cp,
                    'number' => $adopter->number,
                ]
            );

            AdoptionRequest::factory()->create([
                'animal_id' => $animal->id,
                'adopter_id' => $existingAdopter->id,
                'user_id' => $volunteer->id,
            ]);
        }
    }
}
