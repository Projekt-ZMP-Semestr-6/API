<?php

declare(strict_types = 1);

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('password.email');
        $this->user = User::factory()->create()->refresh();

        Notification::fake();
        Notification::assertNothingSent();
    }

    public function test_not_logged_in_user_can_request_reset_password_notification(): void
    {
        $data = [
            'email' => $this->user->email,
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertOk();

        $this->assertSame(
            'We have emailed your password reset link!',
            $response->json('status'),
        );

        Notification::assertSentTo(
            $this->user,
            ResetPassword::class
        );
    }

    public function test_logged_in_user_can_not_request_reset_password_notification(): void
    {
        Sanctum::actingAs($this->user);

        $data = [
            'email' => $this->user->email,
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertRedirect();

        Notification::assertNotSentTo(
            $this->user,
            ResetPassword::class
        );
    }

    public function test_request_with_invalid_email_is_unprocessable(): void
    {
        $data = [
            'email' => $this->faker->email(),
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();

        Notification::assertNothingSent();
    }

    public function test_empty_request_is_unprocessable(): void
    {
        $response = $this->postJson($this->uri);
        $response->assertUnprocessable();

        Notification::assertNothingSent();
    }

    public function test_user_can_reset_password(): void
    {
        $data = [
            'email' => $this->user->email,
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertOk();

        $notifications = Notification::sent(
            $this->user,
            ResetPassword::class
        );

        $token = $notifications[0]->token;

        $data = [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'Pa$$w0rd',
            'password_confirmation' => 'Pa$$w0rd',
        ];

        $this->assertNotTrue(
            Hash::check(
                'Pa$$w0rd',
                $this->user->password
            )
        );

        $response = $this->postJson(
            route('password.update'),
            $data
        );

        $response->assertOk();

        $this->user->refresh();

        $this->assertTrue(
            Hash::check(
                'Pa$$w0rd',
                $this->user->password
            )
        );
    }
}
