<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\AccountInterface;

abstract class Account extends Model implements AccountInterface
{
    protected $fillable = [
        'balance',
        'user_id',
    ];

    abstract public function deposit(float $amount): void;
    abstract public function withdraw(float $amount): void;
    abstract public function getBalance(): float;
    abstract public function applyMonthlyAdjustment(): void;
}
