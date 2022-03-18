<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_user_can_reset_password()
    {
        $user = User::factory()->createOne();
        $data = [
            'email' => $user->email,
        ];

        $response = $this->postJson('/api/auth/forgot-password/send', $data);
        $response->assertOk();

        $this->assertSame(
            'We have emailed your password reset link!',
            $response->json('status'),
        );
    }
}
