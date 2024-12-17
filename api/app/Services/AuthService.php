<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;

class AuthService
{

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                'error' => "Auth failed",
                'statusCode' => 401
            ];
        }

        $token = $user->createToken('AuthToken')->accessToken;

        return [
            'user' => new UserResource($user),
            'access_token' => $token
        ];
    }

    public function register($data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'password' => Hash::make($data['password'])
            ]);

            $token = $user->createToken('AuthToken')->accessToken;

            $this->userService->createUserAccounts($user);

            return [
                'user' => new UserResource($user),
                'access_token' => $token
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }

    public function logout($user)
    {
        $token = $user->token();
        $token->revoke();

        return [
            'message' => 'Logout successful'
        ];
    }
}
