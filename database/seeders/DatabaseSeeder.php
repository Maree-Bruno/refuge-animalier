<?php

namespace Database\Seeders;

use App\Models\Adopter;
use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\Coat;
use App\Models\ContactMessage;
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
            ['key' => 'dog', 'name' => 'Chien'],
            ['key' => 'cat', 'name' => 'Chat'],
            ['key' => 'kid', 'name' => 'Enfant'],
            ['key' => 'baby', 'name' => 'Bébé'],
        ];

        $suitableTypes = collect();
        foreach ($suitableTypesData as $data) {
            $suitableTypes->push(SuitableType::create($data));
        }

        $speciesData = [
            'Chien' => [
                'races' => ['Husky', 'Chihuahua', 'Golden Retriever', 'Berger Australien', 'Cocker'],
                'vaccines' => ['CHPPi', 'Rage', 'Toux du chenil']
            ],
            'Chat' => [
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

            $contactMessages = [
                [
                    'type' => ContactMessage::TYPE_CONTACT,
                    'name' => 'Marie Dubois',
                    'email' => 'marie.dubois@email.com',
                    'phone' => '0471234567',
                    'subject' => 'Question sur les horaires',
                    'message' => 'Bonjour, j\'aimerais connaître vos horaires d\'ouverture pour venir visiter le refuge. Merci !',
                    'status' => ContactMessage::STATUS_NEW,
                ],
                [
                    'type' => ContactMessage::TYPE_CONTACT,
                    'name' => 'Jean Martin',
                    'email' => 'jean.martin@email.com',
                    'phone' => '0482345678',
                    'subject' => 'Renseignements adoption',
                    'message' => 'Bonjour, je souhaiterais avoir plus d\'informations sur le processus d\'adoption. Quelles sont les démarches à suivre ?',
                    'status' => ContactMessage::STATUS_READ,
                ],
                [
                    'type' => ContactMessage::TYPE_CONTACT,
                    'name' => 'Sophie Bernard',
                    'email' => 'sophie.bernard@email.com',
                    'phone' => '0493456789',
                    'subject' => 'Don de matériel',
                    'message' => 'Bonjour, j\'ai des couvertures et des jouets pour animaux dont je voudrais me séparer. Acceptez-vous les dons de matériel ?',
                    'status' => ContactMessage::STATUS_NEW,
                ],
                [
                    'type' => ContactMessage::TYPE_VOLUNTEER,
                    'name' => 'Lucas Petit',
                    'email' => 'lucas.petit@email.com',
                    'phone' => '0464567890',
                    'address' => 'Rue des Lilas',
                    'number' => '42',
                    'cp' => '4000',
                    'city' => 'Liège',
                    'subject' => 'Demande de volontariat',
                    'message' => 'Bonjour, je suis étudiant et j\'aimerais devenir bénévole dans votre refuge. Je suis disponible les week-ends et j\'adore les animaux.',
                    'status' => ContactMessage::STATUS_NEW,
                ],
                [
                    'type' => ContactMessage::TYPE_VOLUNTEER,
                    'name' => 'Emma Leroy',
                    'email' => 'emma.leroy@email.com',
                    'phone' => '0475678901',
                    'address' => 'Avenue du Parc',
                    'number' => '18',
                    'cp' => '4020',
                    'city' => 'Liège',
                    'subject' => 'Demande de volontariat',
                    'message' => 'Bonjour, je travaille à temps partiel et je cherche à m\'impliquer dans une cause qui me tient à cœur. J\'ai de l\'expérience avec les chiens et les chats.',
                    'status' => ContactMessage::STATUS_READ,
                ],
                [
                    'type' => ContactMessage::TYPE_CONTACT,
                    'name' => 'Pierre Durand',
                    'email' => 'pierre.durand@email.com',
                    'phone' => '0486789012',
                    'subject' => 'Animal perdu',
                    'message' => 'Bonjour, j\'ai perdu mon chat hier soir dans le quartier de Cointe. C\'est un chat tigré avec un collier rouge. Avez-vous eu des signalements ?',
                    'status' => ContactMessage::STATUS_ARCHIVED,
                ],
                [
                    'type' => ContactMessage::TYPE_VOLUNTEER,
                    'name' => 'Camille Rousseau',
                    'email' => 'camille.rousseau@email.com',
                    'phone' => '0497890123',
                    'address' => 'Rue de la Gare',
                    'number' => '7',
                    'cp' => '4030',
                    'city' => 'Grivegnée',
                    'subject' => 'Demande de volontariat',
                    'message' => 'Bonjour, je suis retraitée et je dispose de beaucoup de temps libre. J\'aimerais aider au refuge, notamment pour promener les chiens.',
                    'status' => ContactMessage::STATUS_NEW,
                ],
                [
                    'type' => ContactMessage::TYPE_CONTACT,
                    'name' => 'Thomas Lambert',
                    'email' => 'thomas.lambert@email.com',
                    'phone' => '0468901234',
                    'subject' => 'Parrainage',
                    'message' => 'Bonjour, je souhaiterais parrainer un animal. Comment cela fonctionne-t-il ? Quels sont les coûts ?',
                    'status' => ContactMessage::STATUS_READ,
                ],
                [
                    'type' => ContactMessage::TYPE_CONTACT,
                    'name' => 'Julie Moreau',
                    'email' => 'julie.moreau@email.com',
                    'phone' => '0479012345',
                    'subject' => 'Urgence',
                    'message' => 'Bonjour, j\'ai trouvé un chiot abandonné près de chez moi. Il semble blessé. Pouvez-vous le prendre en charge rapidement ?',
                    'status' => ContactMessage::STATUS_NEW,
                ],
                [
                    'type' => ContactMessage::TYPE_VOLUNTEER,
                    'name' => 'Alexandre Blanc',
                    'email' => 'alex.blanc@email.com',
                    'phone' => '0480123456',
                    'address' => 'Boulevard de la Sauvenière',
                    'number' => '125',
                    'cp' => '4000',
                    'city' => 'Liège',
                    'subject' => 'Demande de volontariat',
                    'message' => 'Bonjour, je suis vétérinaire à la retraite et j\'aimerais mettre mes compétences au service de votre refuge.',
                    'status' => ContactMessage::STATUS_ARCHIVED,
                ],
            ];

            foreach ($contactMessages as $messageData) {
                ContactMessage::create(array_merge($messageData, [
                    'send_date' => now()->subDays(rand(0, 10)),
                ]));
            }
        }
    }
}
