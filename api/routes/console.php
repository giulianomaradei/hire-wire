<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Accounts\Account;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('apply-monthly-adjustment', function () {
    $accounts = Account::all();
    foreach ($accounts as $account) {
        $account->applyMonthlyAdjustment();
    }
})->monthly();
