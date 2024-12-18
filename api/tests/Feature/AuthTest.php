<?php

use App\Models\User;
use App\Models\Accounts\Account;
use Laravel\Passport\Passport;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    Artisan::call('migrate:fresh');
    Artisan::call('passport:client --personal --no-interaction');
});


test('user can register', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'cpf' => '12345678900'
    ];

    $this->postJson(route('auth.register'), $userData)
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) use ($userData) {
            $json->where('error', '')
                ->where('response.user.id', 1)
                ->where('response.user.name', $userData['name'])
                ->where('response.user.email', $userData['email'])
                ->where('response.user.cpf', $userData['cpf'])
                ->has('response.user.accounts')
                ->count('response.user.accounts', 3)
                ->where('response.user.accounts.0.type', 'CheckingAccount')
                ->where('response.user.accounts.1.type', 'SavingsAccount')
                ->where('response.user.accounts.2.type', 'InvestmentAccount')
                ->has('response.access_token');
        });

    $this->assertDatabaseHas('users', [
        'email' => $userData['email'],
        'cpf' => $userData['cpf']
    ]);

    // Check if user has exactly 3 accounts
    $user = User::where('email', $userData['email'])->first();
    $accountTypes = $user->accounts->pluck('type')->toArray();

    expect($user->accounts)->toHaveCount(3);
    expect($accountTypes)->toContain('CheckingAccount');
    expect($accountTypes)->toContain('SavingsAccount');
    expect($accountTypes)->toContain('InvestmentAccount');
});

test('user can login', function () {
    $user = User::factory()->create();

    $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password'
    ])->assertStatus(200)->assertJson(function (AssertableJson $json) use ($user) {
        $json->where('error', '')
            ->where('response.user.id', $user->id)
            ->where('response.user.name', $user->name)
            ->where('response.user.email', $user->email)
            ->where('response.user.cpf', $user->cpf)
            ->has('response.access_token');
    });
});

test('user can logout', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('auth.login'), [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(200);

    $token = $response->json()['response']['access_token'];

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson(route('auth.logout'))
        ->assertStatus(200);
});

