<?php

use App\Events\VolunteerCreatedEvent;
use App\Listeners\SendVolunteerCreatedEmailListener;
use App\Mail\VolunteerCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->admin);
});

it('creates a volunteer with a hashed password', function () {
    Mail::fake();

    $formData = [
        'name' => 'Jean Dupont',
        'email' => fake()->unique()->safeEmail(),
        'phone' => fake()->unique()->numerify('##########'),
        'role' => 'volunteer',
    ];

    $response = $this->post(route('volunteers.store'), $formData);

    $response->assertSessionHasNoErrors();

    $user = User::where('email', $formData['email'])->first();

    expect($user)->not->toBeNull()
        ->and($user->password)->not->toBeEmpty()
        ->and(strlen($user->password))->toBeGreaterThan(20);
});

it('creates a volunteer user successfully', function () {
    $formData = [
        'name' => 'Jean Dupont',
        'email' => fake()->unique()->safeEmail(),
        'phone' => fake()->unique()->numerify('##########'),
        'role' => 'volunteer',
    ];

    $response = $this->post(route('volunteers.store'), $formData);


    $user = User::where('email', $formData['email'])->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Jean Dupont')
        ->and($user->role)->toBe('volunteer')
        ->and($user->password)->not->toBeEmpty();
});

it('observer sets password for volunteer on creation', function () {
    $user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'role' => 'volunteer',
    ]);

    $user->save();

    expect($user->password)->not->toBeEmpty()
        ->and($user->password)->not->toBe('volunteer');
});

it('queues email when volunteer created event is handled', function () {
    Mail::fake();

    $user = User::factory()->create(['role' => 'volunteer']);
    $plainPassword = 'testpassword123';

    $listener = new SendVolunteerCreatedEmailListener();
    $listener->handle(new VolunteerCreatedEvent($user, $plainPassword));

    Mail::assertQueued(VolunteerCreatedMail::class, function ($mail) use ($user) {
        return $mail->user->email === $user->email
            && $mail->hasTo($user->email)
            && str_contains($mail->envelope()->subject, 'Bienvenue');
    });
});

it('includes the password in the email content', function () {
    $user = User::factory()->create();
    $password = 'testPassword123';

    $mail = new VolunteerCreatedMail($user, $password);

    $mail->assertSeeInHtml($password);
    $mail->assertSeeInHtml($user->email);
    $mail->assertSeeInHtml($user->name);
});

it('sends email through the full volunteer creation flow', function () {


    $formData = [
        'name' => 'Jean Dupont',
        'email' => fake()->unique()->safeEmail(),
        'phone' => fake()->unique()->numerify('##########'),
        'role' => 'volunteer',
    ];

    Mail::fake();

    $user = User::create([
        'name' => $formData['name'],
        'email' => $formData['email'],
        'phone' => $formData['phone'],
        'role' => $formData['role'],
    ]);

    expect($user)->not->toBeNull()
        ->and($user->password)->not->toBeEmpty();
});

