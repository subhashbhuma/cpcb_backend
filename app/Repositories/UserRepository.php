<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function findAll()
    {
        $currentRole = Auth::user()->roles->first()->name;
        return User::whereHas('roles', function ($query) use ($currentRole) {
            if ($currentRole == 'SUPERADMIN') {
                $query->where('name', '!=', 'SUPERADMIN');
            } else {
                $query->whereNotIn('name', ['ADMIN', 'SUPERADMIN']);
            }
            $query->where('name', '!=', 'EMPLOYEE');
        })->with(['roles', 'division']);
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function update($data, $id)
    {
        $user = User::find($id);
        if ($user) {
            $result = $user->update($data);
            if (!$result) {
                return false;
            }
            return $user;
        }
        return false;
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
