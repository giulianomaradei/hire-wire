<?php

use App\Models\Accounts\Account;
use App\Models\Accounts\CheckingAccount;
use App\Models\Accounts\InvestmentAccount;
use App\Models\Accounts\SavingsAccount;
use App\Models\User;
use Laravel\Passport\Passport;

beforeEach(function () {
    Artisan::call('migrate:fresh');
    Artisan::call('passport:client --personal --no-interaction');
});

test('user deposit money', function () {
    $user = User::factory()->create();
    $checkingAccount = CheckingAccount::create([
        'balance' => 0,
        'user_id' => $user->id,
    ]);

    Passport::actingAs($user);

    $this->postJson(route('accounts.deposit', $checkingAccount->id), ['amount' => 100])
        ->assertStatus(200);

    $depositIncrement = $checkingAccount->getDepositIncrementAttribute();

    $checkingAccount->refresh();
    expect($checkingAccount->balance)->toBe(100 + $depositIncrement);
});

test('accounts receive monthly adjustment', function () {
    $user = User::factory()->create();

    // Create accounts with initial balances
    $checkingAccount = CheckingAccount::create([
        'balance' => 1000,
        'user_id' => $user->id,
    ]);

    $savingsAccount = SavingsAccount::create([
        'balance' => 1000,
        'user_id' => $user->id,
    ]);

    $investmentAccount = InvestmentAccount::create([
        'balance' => 1000,
        'user_id' => $user->id,
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
