<?php

namespace App\Services;

use App\Models\Accounts\Account;
use App\Models\Accounts\CheckingAccount;
use App\Models\Accounts\InvestmentAccount;
use App\Models\Accounts\SavingsAccount;

class UserService
{
    public function createUserAccounts($user)
    {
        try {
            /// create one account of each type
            $checkingAccount = CheckingAccount::create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);

            $savingsAccount = SavingsAccount::create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);

            $investmentAccount = InvestmentAccount::create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
