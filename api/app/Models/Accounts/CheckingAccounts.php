<?php

namespace App\Models;

class CheckingAccount extends Account
{
    protected $table = 'checking_accounts';

    private const MONTHLY_ADJUSTMENT_RATE = 0.001;
    private const DEPOSIT_INCREMENT = 0.5;

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function deposit(float $amount): void
    {
        if($amount <= 0) {
            throw new \Exception('Valor de depósito inválido');
        }

        $this->balance += $amount + self::DEPOSIT_INCREMENT;
        $this->save();
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new \Exception('O valor do saque deve ser positivo');
        }
        if ($this->balance < $amount) {
            throw new \Exception('Saldo insuficiente');
        }

        $this->balance -= $amount;
        $this->save();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }


    public function getMonthlyAdjustment(): float
    {
        return self::MONTHLY_ADJUSTMENT_RATE;
    }

    public function applyMonthlyAdjustment(): void
    {
        $this->balance += $this->balance * self::MONTHLY_ADJUSTMENT_RATE;
        $this->save();
    }
}