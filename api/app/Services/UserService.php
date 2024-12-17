<?php

namespace App\Services;

use App\Models\Accounts\CheckingAccount;
use App\Models\Accounts\SavingsAccount;
use App\Models\Accounts\InvestmentAccount;

class UserService
{
    public function createUserAccounts($user)
    {
        try {
            $user->checkingAccounts()->create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);

            $user->savingsAccounts()->create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);

            $user->investmentAccounts()->create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error creating user accounts');
        }
    }
}
