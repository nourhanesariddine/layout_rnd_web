<?php

namespace App\Repositories;


use App\Interfaces\UserRepositoryInterface;


use App\Models\User;
use twa\apiutils\Traits\APITrait;

class UserRepository implements UserRepositoryInterface
{
    
    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)
            ->whereNull('deleted_at')
            ->first();

        return $user;
    }

 
}
