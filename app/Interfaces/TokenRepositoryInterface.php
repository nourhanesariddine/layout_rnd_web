<?php

namespace App\Interfaces;

interface TokenRepositoryInterface
{
    public function createUserToken();
    public function createAccessToken($id, $type, $duration_minutes = 525600);

}