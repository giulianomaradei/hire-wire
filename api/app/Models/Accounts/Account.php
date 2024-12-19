<?php

namespace App\Models\Accounts;

use App\Contracts\AccountInterface;
use Illuminate\Database\Eloquent\Model;/// Eu utilizei STI, para lidar com as contas, onde eu possuo apenas uma tabela para as contas e os tipos, para o nosso sistema que é simples funciona bem, porém também poderiamos usar coisas como o polimorfismo das relações do laravel. Essa maneira é funcional já que as diferentes contas não possuem colunas diferentes, apenas o comportamente, dessa maneira conseguimos ter uma logica especifca para cada. Caso contas precisasem de campos diferentes, eu utilizaria o polimorfismo das relações do laravel ou outras implementações como table per class.

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
            if ($amount <= 0) {
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
        $className = 'App\\Models\\Accounts\\'.$this->type;

        if (! class_exists($className)) {
            throw new \Exception("Invalid account type: {$this->type}");
        }

        $rate = $className::MONTHLY_ADJUSTMENT_RATE;
        $adjustment = $this->balance * $rate;
        $this->balance += $adjustment;
        $this->save();
    }

    // Adicionando este método para suportar a serialização de tipos de contas
    public function newFromBuilder($attributes = [], $connection = null)
    {
        if (isset($attributes->type)) {
            $class = 'App\\Models\\Accounts\\' . $attributes->type;

            if (class_exists($class)) {
                $model = new $class;
                $model->exists = true;
                $model->setRawAttributes((array) $attributes, true);
                $model->setConnection($connection ?: $this->getConnectionName());
                return $model;
            }
        }

        return parent::newFromBuilder($attributes, $connection);
    }
}
