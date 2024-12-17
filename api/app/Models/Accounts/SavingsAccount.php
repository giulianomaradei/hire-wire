<?php

namespace App\Models\Accounts;

class SavingsAccount extends Account
{
    protected $table = 'savings_accounts';

    protected const MONTHLY_ADJUSTMENT_RATE = 0.00001;

}