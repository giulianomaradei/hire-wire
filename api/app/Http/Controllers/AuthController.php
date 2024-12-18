<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (is_array($result) && isset($result['error'])) {
            return response()->apiError($result['error']);
        }

        return response()->apiSuccess($result);
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        if (is_array($result) && isset($result['error'])) {
            return response()->apiError($result['error']);
        }

        return response()->apiSuccess($result);
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout($request->user());

        return response()->apiSuccess($result);
    }
}
