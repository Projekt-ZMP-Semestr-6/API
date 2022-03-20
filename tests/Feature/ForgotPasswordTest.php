<?php

namespace Tests\Feature;

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

    public function test_not_logged_in_user_can_request_reset_password_notification()
    {
        $user = User::factory()->createOne();
        $user->refresh();

        $data = [
            'email' => $user->email,
        ];

        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->postJson(route('password.email'), $data);
        $response->assertOk();

        $this->assertSame(
            'We have emailed your password reset link!',
            $response->json('status'),
        );

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_logged_in_user_can_not_request_reset_password_notification()
    {
        $user = User::factory()->createOne();
        $user->refresh();

        Sanctum::actingAs($user);

        $data = [
            'email' => $user->email,
        ];

        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->postJson(route('password.email'), $data);
        $response->assertRedirect();

        Notification::assertNotSentTo($user, ResetPassword::class);
    }

    public function test_request_with_invalid_email_is_unprocessable()
    {
        $data = [
            'email' => $this->faker->email(),
        ];

        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->postJson(route('password.email'), $data);
        $response->assertUnprocessable();

        Notification::assertNothingSent();
    }

    public function test_empty_request_is_unprocessable()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->postJson(route('password.email'));
        $response->assertUnprocessable();

        Notification::assertNothingSent();
    }

    public function test_user_can_reset_password()
    {
        $user = User::factory()->createOne();
        $user->refresh();

        $data = [
            'email' => $user->email,
        ];

        Notification::fake();

        $response = $this->postJson(route('password.email'), $data);
        $response->assertOk();

        $notifications = Notification::sent($user, ResetPassword::class);
        $token = $notifications[0]->token;

        $data = [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Pa$$w0rd',
            'password_confirmation' => 'Pa$$w0rd',
        ];

        $this->assertNotTrue(Hash::check('Pa$$w0rd', $user->password));

        $response = $this->postJson(route('password.update'), $data);
        $response->assertOk();

        $user->refresh();
        $this->assertTrue(Hash::check('Pa$$w0rd', $user->password));
    }

}
