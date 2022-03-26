<?php

declare(strict_types = 1);

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateNameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|min:4|required',
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
