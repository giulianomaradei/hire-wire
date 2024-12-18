<?php

use App\Models\User;
use App\Models\Accounts\Account;
use Laravel\Passport\Passport;
use Illuminate\Testing\Fluent\AssertableJson;

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
