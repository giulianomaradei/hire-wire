<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\AccountInterface;
use App\Models\Accounts\CheckingAccount;
use App\Models\Accounts\SavingsAccount;
use App\Models\Accounts\InvestmentAccount;

/// Eu utilizei STI, para lidar com as contas, onde eu possuo apenas uma tabela para as contas e os tipos, para o nosso sistema que é simples funciona bem, porém também poderiamos usar coisas como o polimorfismo das relações do laravel. Essa maneira é funcional já que as diferentes contas não possuem colunas diferentes, apenas o comportamente, dessa maneira conseguimos ter uma logica especifca para cada. Caso contas precisasem de campos diferentes, eu utilizaria o polimorfismo das relações do laravel ou outras implementações como table per class.
class Account extends Model implements AccountInterface
{
    protected $table = 'accounts';

    protected $fillable = [
        'balance',
        'user_id',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = class_basename($model);
            }
        });
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
        // Get the fully qualified class name
        $className = 'App\\Models\\Accounts\\' . $this->type;

        if (!class_exists($className)) {
            throw new \Exception("Invalid account type: {$this->type}");
        }

        $rate = $className::MONTHLY_ADJUSTMENT_RATE;
        $adjustment = $this->balance * $rate;
        $this->balance += $adjustment;
        $this->save();
    }

    public function getAccountTypeAttribute(): string
    {
        return class_basename($this->accountable_type) . 'Account';
    }
}