<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Models\Accounts\Account;

class AccountsController extends Controller
{
    public function deposit(DepositRequest $request, Account $account)
    {
        try {
            $account->deposit($request->amount);
            $account->refresh();
        } catch (\Exception $e) {
            return response()->apiError($e->getMessage());
        }

        return response()->apiSuccess($account);
    }
}
