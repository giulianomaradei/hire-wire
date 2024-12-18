<?php

use App\Models\User;
use App\Models\Accounts\Account;
use Laravel\Passport\Passport;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Accounts\CheckingAccount;
use App\Models\Accounts\SavingsAccount;
use App\Models\Accounts\InvestmentAccount;

beforeEach(function () {
    Artisan::call('migrate:fresh');
    Artisan::call('passport:client --personal --no-interaction');
});


test('user deposit money', function () {
    $user = User::factory()->create();
    $account = $user->accounts()->create([
        'type' => 'CheckingAccount',
        'balance' => 0
    ]);

    Passport::actingAs($user);

    $this->postJson(route('accounts.deposit', $account->id), ['amount' => 100])
        ->assertStatus(200);

    $account->refresh();
    expect($account->balance)->toBe(100);
});

test('accounts receive monthly adjustment', function () {
    $user = User::factory()->create();

    // Create accounts with initial balances
    $checkingAccount = CheckingAccount::create([
        'balance' => 1000,
        'user_id' => $user->id
    ]);

    $savingsAccount = SavingsAccount::create([
        'balance' => 1000,
        'user_id' => $user->id
    ]);

    $investmentAccount = InvestmentAccount::create([
        'balance' => 1000,
        'user_id' => $user->id
    ]);

    // Run the monthly adjustment command
    Artisan::call('apply-monthly-adjustment');

    // Refresh the account models to get updated balances
    $checkingAccount->refresh();
    $savingsAccount->refresh();

    // CheckingAccount has 0.1% monthly adjustment (0.001)
    expect($checkingAccount->balance)->toBe(1001);

    // SavingsAccount has 0.001% monthly adjustment (0.00001)
    expect($savingsAccount->balance)->toBe(1000.01);
});