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
            $checkingAccount = CheckingAccount::create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);
            $user->accounts()->save($checkingAccount);

            $savingsAccount = SavingsAccount::create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);
            $user->accounts()->save($savingsAccount);


            $investmentAccount = InvestmentAccount::create([
                'balance' => 0,
                'user_id' => $user->id,
            ]);
            $user->accounts()->save($investmentAccount);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
