<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\AccountInterface;

/// Em termos de POO essa classe deveria ser abstrata, afinal nao existe uma conta sem um tipo, porém em razão do laravel nos não podemos ter uma classe abstrata funcionando numa relação como model, então mantemos ela como uma classe concreta, existem outras maneiras porém acredito que essa o codigo fica mais limpo e funcional.
class Account extends Model implements AccountInterface
{
    protected $fillable = [
        'balance',
        'user_id',
    ];

    protected $appends = ['account_type'];

    protected const MONTHLY_ADJUSTMENT_RATE = 0.0;

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit(float $amount): void
    {
        try {
            if($amount <= 0) {
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
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            $this->save();
        } else {
            throw new \Exception('Insufficient balance');
        }
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function applyMonthlyAdjustment(): void
    {
        $rate = $this->getMonthlyAdjustmentRate();
        $adjustment = $this->balance * $rate;
        $this->balance += $adjustment;
        $this->save();
    }

    protected function getMonthlyAdjustmentRate(): float
    {
        return static::MONTHLY_ADJUSTMENT_RATE;
    }

    public function getAccountTypeAttribute(): string
    {
        return class_basename($this->accountable_type) . 'Account';
    }
}