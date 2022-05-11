<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\UserLogin;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Post(
 * path="/api/auth/login",
 * summary="Log in",
 * description="Login by email, password",
 * operationId="login",
 * tags={"Auth"},
 * @OA\RequestBody(
 *      required=true,
 *      description="Pass user's credentials to log in",
 *      @OA\JsonContent(
 *          required={"email", "password", "device_name"},
 *          @OA\Property(property="email", type="email", example="test@email.com"),
 *          @OA\Property(property="password", type="password", example="password123"),
 *          @OA\Property(property="device_name", type="string", ref="#/components/schemas/DeviceName"),
 *      ),
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(ref="#/components/schemas/BearerToken"),
 * ),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\JsonContent(
 *          oneOf={
 *              @OA\Schema(ref="#/components/schemas/attribiuteProblem"),
 *              @OA\Schema(ref="#/components/schemas/incorrectCredentials"),
 *              @OA\Schema(ref="#/components/schemas/verifyEmail"),
 *          },
 *      )
 * ))
 *
 * @OA\Schema(
 * schema="DeviceName",
 * oneOf={
 *      @OA\Property(property="device_name", type="string", example="desktop"),
 *      @OA\Property(property="device_name", type="string", example="web"),
 *      @OA\Property(property="device_name", type="string", example="mobile"),
 * }),
 *
 * @OA\Schema(
 * schema="BearerToken",
 * @OA\Property(property="Bearer", type="bearerToken", example="1|9o7QEJY84otL2hjS6MuiNOUQZgQ0D43IhDFDfZuI"),
 * ),
 *
 * @OA\Schema(
 * schema="incorrectCredentials",
 * @OA\Property(
 * type="string",
 * example="Incorrect credentials",
 * )),
 *
 * @OA\Schema(
 * schema="attribiuteProblem",
 * @OA\Property(
 *      property="attribiute_name",
 *      type="array",
 *      @OA\Items(
 *          type="string",
 *          example="problem_message",
 *      ),
 * ),
 * @OA\Property(
 *      property="other_attribiute_name",
 *      type="array",
 *      @OA\Items(
 *          type="string",
 *          example="problem_message",
 *      ),
 * )),
 *
 * @OA\Schema(
 * schema="verifyEmail",
 * type="string",
 * example="Email not verified."
 * )
 */
class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, UserLogin $service): JsonResponse
    {
        $validated = $request->validated();

        $token = $service->login($validated);

        return new JsonResponse(['Bearer' => $token]);
    }
}
