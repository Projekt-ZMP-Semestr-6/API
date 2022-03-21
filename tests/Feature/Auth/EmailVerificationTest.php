<?php

declare(strict_types = 1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake(Registered::class);
        Event::assertNothingDispatched();

        Notification::fake();
        Notification::assertNothingSent();

        $this->user = $this->registerUser();
    }

    public function test_successful_registration_fires_event(): void
    {
        Event::assertDispatched(Registered::class);
    }

    public function test_successful_registration_triggers_mail_sending(): void
    {
        $event = new Registered($this->user);
        $listener = new SendEmailVerificationNotification();

        $listener->handle($event);

        Notification::assertSentTo($this->user, VerifyEmail::class);
    }

    public function test_unverified_user_can_not_enter_specific_route(): void
    {
        $event = new Registered($this->user);
        $listener = new SendEmailVerificationNotification();

        $listener->handle($event);

        Notification::assertSentTo($this->user, VerifyEmail::class);

        Sanctum::actingAs($this->user);

        $this->assertNull($this->user->email_verified_at);

        $response = $this->getJson(route('user.info'));
        $response->assertForbidden();
    }

    protected function registerUser(): User
    {
        $data = [
            'email' => 'test@test.com',
            'name' => 'bob4',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $data);
        $response->assertCreated();

        return User::find($response->json('id'));
    }
}
