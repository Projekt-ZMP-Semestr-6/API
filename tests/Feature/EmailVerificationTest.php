<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful_registration_fires_event(): void
    {
        Event::fake(Registered::class);
        Event::assertNothingDispatched();

        $this->registerUser();

        Event::assertDispatched(Registered::class);
    }

    public function test_successful_registration_triggers_mail_sending(): void
    {
        $user = $this->registerUser();

        Notification::fake();
        Notification::assertNothingSent();

        $event = new Registered($user);
        $listener = new SendEmailVerificationNotification();
        $listener->handle($event);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_unverified_user_can_not_enter_specific_route(): void
    {
        $user = $this->registerUser();

        Notification::fake();
        Notification::assertNothingSent();

        $event = new Registered($user);
        $listener = new SendEmailVerificationNotification();
        $listener->handle($event);

        Notification::assertSentTo($user, VerifyEmail::class);

        Sanctum::actingAs($user);
        $this->assertNull($user->email_verified_at);

        $response = $this->getJson('/api/user');
        $response->assertForbidden();
    }

    protected function registerUser(): User
    {
        $data = [
            'email' => $this->faker->email(),
            'name' => $this->faker->name(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertCreated();

        return User::find($response->json('id'));
    }
}
