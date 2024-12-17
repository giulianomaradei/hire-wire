<?php

namespace App\Models;

class SavingsAccount extends Account
{
    protected $table = 'savings_accounts';

    private const MONTHLY_ADJUSTMENT_RATE = 0.00001;

    public function account()
    {
        return $this->morphOne(AccountBase::class, 'accountable');
    }

    public function deposit(float $amount): void
    {
        if($amount < 0) {
            throw new \Exception('Valor de depósito inválido');
        }

        $this->balance += $amount;
        $this->save();
    }

    public function withdraw(float $amount): void
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            $this->save();
        } else {
            throw new \Exception('Saldo insuficiente');
        }
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function applyMonthlyAdjustment(): void
    {
        $this->balance += $this->balance * self::MONTHLY_ADJUSTMENT_RATE;
        $this->save();
    }
}