<?php

namespace App\Services;

use App\Models\Accounts\Account;
use App\Models\Accounts\CheckingAccount;
use App\Models\Accounts\SavingsAccount;
use App\Models\Accounts\InvestmentAccount;

class UserService
{
    public function createUserAccounts($user)
    {
        try {
            /// create one account of each type
            $checkingAccount = CheckingAccount::create();
            $user->accounts()->create([
                'balance' => 0,
                'user_id' => $user->id,
                'accountable_type' => CheckingAccount::class,
                'accountable_id' => $checkingAccount->id,
            ]);

            $savingsAccount = SavingsAccount::create();
            $user->accounts()->create([
                'balance' => 0,
                'user_id' => $user->id,
                'accountable_type' => SavingsAccount::class,
                'accountable_id' => $savingsAccount->id,
            ]);

            $investmentAccount = InvestmentAccount::create();
            $user->accounts()->create([
                'balance' => 0,
                'user_id' => $user->id,
                'accountable_type' => InvestmentAccount::class,
                'accountable_id' => $investmentAccount->id,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
