<?php

declare(strict_types = 1);

namespace App\Http\Requests\Auth;

use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'email|required|exists:users,email',
            'password' => 'string|required',
            'device_name' => 'string|in:desktop,phone,web|required'
        ];
    }

    protected function passedValidation(): void
    {
        $email = $this->validated('email');
        $user = User::whereEmail($email)->first();

        if(! $user->hasVerifiedEmail()) {
            throw new EmailNotVerifiedException;
        };
    }

    protected function failedValidation(Validator $validator): void
    {
        $response = new JsonResponse(
            $validator->errors(),
            422
        );

        throw new HttpResponseException($response);
    }
}
