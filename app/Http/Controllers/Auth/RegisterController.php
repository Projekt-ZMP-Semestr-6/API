<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\UserCreationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * @OA\Post(
 * path="/api/auth/register",
 * summary="Register",
 * description="Register new account",
 * operationId="register",
 * tags={"auth"},
 * @OA\RequestBody(
 *      required=true,
 *      description="Pass user credentials",
 *      @OA\JsonContent(
 *          required={"email", "name", "password", "password_confirmation"},
 *          @OA\Property(
 *              property="email",
 *              type="string",
 *              format="email",
 *              example="test@test.com"
 *          ),
 *          @OA\Property(
 *              property="name",
 *              type="string",
 *              example="Greg"
 *          ),
 *          @OA\Property(
 *              property="password",
 *              type="string",
 *              format="password",
 *              example="password123"
 *          ),
 *          @OA\Property(
 *              property="password_confirmation",
 *              type="string",
 *              format="password",
 *              example="password123"
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=201,
 *      description="Created",
 *      @OA\JsonContent(
 *          ref="#/components/schemas/User",
 *      ),
 * ),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="attribiute_name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="problem_message",
 *              ),
 *          ),
 *          @OA\Property(
 *              property="other_attribiute_name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="problem_message",
 *              ),
 *          ),
 *      ),
 * ))
 */
class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        try {
            $user = User::create($validated);
        } catch(Throwable) {
            throw new UserCreationException();
        }

        event(new Registered($user));

        return new JsonResponse($user, 201);
    }
}
