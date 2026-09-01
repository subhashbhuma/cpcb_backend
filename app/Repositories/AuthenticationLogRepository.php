<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class AuthenticationLogRepository
{
    public function findAll()
    {
        return Auth::user()->authentications()->latest();
    }
}
