<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\AccountInterface;

abstract class Account extends Model implements AccountInterface
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    abstract public function deposit(float $amount): void;
    abstract public function withdraw(float $amount): void;
    abstract public function getBalance(): float;
    abstract public function applyMonthlyAdjustment(): void;
    abstract public function getMonthlyAdjustment(): float;
}