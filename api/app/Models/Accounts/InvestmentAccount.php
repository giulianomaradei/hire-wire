<?php

namespace App\Models\Accounts;

class InvestmentAccount extends Account
{
    protected const MONTHLY_ADJUSTMENT_RATE = 0.001;

    private const DEPOSIT_INCREMENT = 0.5;

    protected $appends = ['deposit_increment'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'InvestmentAccount';
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
        return parent::newQuery()->where('type', 'InvestmentAccount');
    }

    public function getDepositIncrementAttribute(): float
    {
        return self::DEPOSIT_INCREMENT;
    }
}
