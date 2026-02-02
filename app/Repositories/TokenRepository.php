<?php

namespace App\Repositories;


use App\Interfaces\TokenRepositoryInterface;
use App\Models\AccessToken;
use Illuminate\Support\Str;

class TokenRepository implements TokenRepositoryInterface
{



    public function createAccessToken($id, $type , $duration_minutes = 525600)
    {

        $token = sprintf(
            '%s%s%s',
            '',
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy));

        $access_token = new AccessToken();
        $access_token->token = $id . "|" . $token;
        $access_token->tokenable_id = $id;
        $access_token->tokenable_type = $type;
        $access_token->expires_at = now()->addMinutes($duration_minutes);
        $access_token->save();

        return $access_token;

    }
    


}