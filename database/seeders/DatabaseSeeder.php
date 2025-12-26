<?php

namespace Database\Seeders;

use App\Models\Adopter;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\Race;
use App\Models\Specie;
use App\Models\SuitableType;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
        $coatNames = ['Blanc', 'Noir', 'Doré', 'Tâché', 'Tricolore', 'Brun'];
        $coats = collect();
        foreach ($coatNames as $name) {
            $coats->push(Coat::create(['name' => $name]));
        }
        $suitableTypesData = [
            ['key' => 'dog', 'label' => 'Chien'],
            ['key' => 'cat', 'label' => 'Chat'],
            ['key' => 'kid', 'label' => 'Enfant'],
            ['key' => 'baby', 'label' => 'Bébé'],
        ];

        $suitableTypes = collect();
        foreach ($suitableTypesData as $data) {
            $suitableTypes->push(SuitableType::create($data));
        }

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

            foreach ($data['races'] as $raceName) {
                Race::create([
                    'name' => $raceName,
                    'specie_id' => $specie->id,
                ]);
            }

            foreach ($data['vaccines'] as $vaccineName) {
                Vaccine::create([
                    'name' => $vaccineName,
                    'specie_id' => $specie->id,
                ]);
            }
        }

        $animals = collect();
        for ($i = 0; $i < 20; $i++) {
            $race = Race::inRandomOrder()->first();

            $animal = Animal::factory()->create([
                'race_id' => $race->id,
                'coat_id' => $coats->random()->id,
                'user_id' => $volunteer->id,
            ]);

            $compatibleVaccines = $race->specie->vaccines;
            if ($compatibleVaccines->count() > 0) {
                $animal->vaccines()->attach(
                    $compatibleVaccines->random(rand(0, $compatibleVaccines->count()))->pluck('id')->toArray()
                );
            }

            $randomSuitableTypes = $suitableTypes->random(rand(1, 4));
            $animal->suitableTypes()->attach($randomSuitableTypes->pluck('id')->toArray());

            $animals->push($animal);
        }

        $adopters = Adopter::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            $animal = $animals->random();

            $adopter = $adopters->random();

            $existingAdopter = Adopter::firstOrCreate(
                ['email' => $adopter->email],
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
