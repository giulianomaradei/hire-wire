<?php

namespace App\Models\Accounts;

class InvestmentAccount extends Account
{
    protected $table = 'investment_accounts';

    protected const MONTHLY_ADJUSTMENT_RATE = 0.001;
    private const DEPOSIT_INCREMENT = 0.5;

    public function deposit(float $amount): void
    {
        try {
            parent::deposit($amount);

            $this->balance += self::DEPOSIT_INCREMENT;
            $this->save();
        } catch (\Exception $e) {
            throw new \Exception('Error depositing');
        }
    }
}