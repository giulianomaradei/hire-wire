<?php

namespace App\Models\Accounts;

class SavingsAccount extends Account
{
    protected $table = 'savings_accounts';

    private const MONTHLY_ADJUSTMENT_RATE = 0.00001;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit(float $amount): void
    {
        try {
            if($amount < 0) {
                throw new \Exception('Invalid deposit value');
            }

            $this->balance += $amount;
            $this->save();
        } catch (\Exception $e) {
            throw new \Exception('Error depositing');
        }
    }

    public function withdraw(float $amount): void
    {
        try {
            if ($this->balance >= $amount) {
                $this->balance -= $amount;
                $this->save();
        } else {
                throw new \Exception('Insufficient balance');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error withdrawing');
        }
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function applyMonthlyAdjustment(): void
    {
        try {
            $this->balance += $this->balance * self::MONTHLY_ADJUSTMENT_RATE;
            $this->save();
        } catch (\Exception $e) {
            throw new \Exception('Error applying monthly adjustment');
        }
    }
}