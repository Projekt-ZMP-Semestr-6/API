<?php

namespace App\Http\Controllers;

use App\Exceptions\ChangeNameException;
use App\Http\Requests\ChangeNameRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class ChangeNameController extends Controller
{
    public function __invoke(ChangeNameRequest $request): JsonResponse
    {
        $newName = $request->validated('name');
        $user = $request->user('sanctum');

        try {
            $user->name = $newName;
            $user->save();
        } catch (Throwable) {
            throw new ChangeNameException;
        }

        return new JsonResponse('Name updated!');
    }
}
