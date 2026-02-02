<?php

namespace App\Interfaces;

interface TokenRepositoryInterface
{
    public function createAccessToken($id, $type, $duration_minutes = 525600);

}