<?php

namespace App\Models\Accounts;

class SavingsAccount extends Account
{
    protected const MONTHLY_ADJUSTMENT_RATE = 0.00001;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'SavingsAccount';
        });
    }

    public function newQuery()
    {
        return parent::newQuery()->where('type', 'SavingsAccount');
    }
}
