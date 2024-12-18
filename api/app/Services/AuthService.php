<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return [
                'error' => 'Auth failed',
                'statusCode' => 401,
            ];
        }

        $token = $user->createToken('AuthToken')->accessToken;

        return [
            'user' => new UserResource($user),
            'access_token' => $token,
        ];
    }

    public function register($data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('AuthToken')->accessToken;

            try {
                $this->userService->createUserAccounts($user);
            } catch (\Exception $e) {
                return [
                    'error' => $e->getMessage(),
                    'statusCode' => 500,
                ];
            }

            return [
                'user' => new UserResource($user),
                'access_token' => $token,
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'statusCode' => 500,
            ];
        }
    }

    public function logout($user)
    {
        $token = $user->token();
        $token->revoke();

        return [
            'message' => 'Logout successful',
        ];
    }
}
