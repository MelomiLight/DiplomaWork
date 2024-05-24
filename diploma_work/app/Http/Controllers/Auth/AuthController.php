<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\RunInformationResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     *
     * @throws Exception
     * @OA\Post(
     *      path="/api/register",
     *      summary="Регистрация",
     *      tags={"Авторизация"},
     *
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="user"),
     *              @OA\Property(property="email", type="string", example="user230@crocos.kz"),
     *              @OA\Property(property="password", type="string", example="12345678"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="your_token"),
     *          ),
     *      ),
     *      @OA\Response(
     *         response=500,
     *         description="Could not create user"
     *      ),
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->service->createUser($request);

        return response()->json(['token' => $token], 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return UserResource|JsonResponse
     *
     * @throws Exception
     * @OA\Post(
     *      path="/api/login",
     *      summary="Логин",
     *      tags={"Авторизация"},
     *
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="user230@crocos.kz"),
     *              @OA\Property(property="password", type="string", example="12345678"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(type="string", example="user_info"))),
     *              @OA\Property(property="token", type="string", example="your_token"),
     *          ),
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="These credentials do not match our records."
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Could not create token"
     *       ),
     * )
     */
    public function login(LoginRequest $request): UserResource|JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => __('messages.failed')], 401);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        $token = $this->service->loginUser($user);
        return (new UserResource($user))->additional(['token' => $token]);
    }

    /**
     *
     * @param ForgotRequest $request
     * @return JsonResponse
     *
     * @throws Exception
     * @OA\Post(
     *      path="/api/forgot",
     *      summary="Забыл пароль",
     *      tags={"Авторизация"},
     *
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(property="email", type="string", example="user230@crocos.kz"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="We have emailed your password reset link.",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Could not send mail or could not create reset code"
     *       ),
     * )
     */
    public function forgotPassword(ForgotRequest $request): JsonResponse
    {
        $user = $this->service->forgotPassword($request);

        $this->service->sendMailToUser($user);

        return response()->json(['message' => __('messages.sent')]);
    }

    /**
     *
     * @param ChangeRequest $request
     * @return JsonResponse
     *
     * @throws Exception
     * @OA\Post(
     *      path="/api/password/reset",
     *      summary="Поменять пароль",
     *      tags={"Авторизация"},
     *
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password", "reset_code"},
     *              @OA\Property(property="email", type="string", example="user230@crocos.kz"),
     *              @OA\Property(property="password", type="string", example="12345678"),
     *              @OA\Property(property="reset_code", type="string", example="f28adw"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Your password has been reset.",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Could not change password"
     *       ),
     * )
     */
    public function changePassword(ChangeRequest $request): JsonResponse
    {
        $this->service->changePassword($request);

        return response()->json(['message' => __('messages.reset')]);
    }

    /**
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/logout",
     *      summary="Поменять пароль",
     *      tags={"Авторизация"},
     *
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *       @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          example="Bearer your_token"
     *       ),
     *
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="error message"
     *       ),
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            Auth::user()->currentAccessToken()->delete();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json(['message' => __('messages.logout_success')]);
    }
}
