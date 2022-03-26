<?php

declare(strict_types = 1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class SendResetPasswordNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'email|required',
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
}
