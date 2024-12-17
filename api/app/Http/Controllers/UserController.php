<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $userData = UserResource::make($request->user());

        return response()->apiSuccess($userData);
    }
}
