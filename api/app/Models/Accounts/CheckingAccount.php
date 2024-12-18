<?php

namespace App\Models\Accounts;

class CheckingAccount extends Account
{
    protected const MONTHLY_ADJUSTMENT_RATE = 0.001;

    private const DEPOSIT_INCREMENT = 0.5;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'CheckingAccount';
        });
    }

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

    public function newQuery()
    {
        return parent::newQuery()->where('type', 'CheckingAccount');
    }
}
