<?php

namespace App\Contracts;

interface AccountInterface
{
    public function deposit(float $amount): void;
    public function withdraw(float $amount): void;
    public function getBalance(): float;
    public function applyMonthlyAdjustment(): void;
}