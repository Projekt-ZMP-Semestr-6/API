<?php

declare(strict_types = 1);

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ResendEmailVerificationNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'email|required|exists:users,email',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $response = new JsonResponse(
            $validator->errors(),
            422
        );

        throw new HttpResponseException($response);
    }

    protected function passedValidation(): void
    {
        $this->setUserResolver(function () {
            $email = $this->validated('email');
            $user = User::whereEmail($email)->first();

            return $user;
        });
    }
}
