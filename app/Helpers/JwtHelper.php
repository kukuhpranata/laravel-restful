<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;

class JwtHelper
{
    public function getPayload(): array
    {
        $token = JWTAuth::getToken();
        $payload = JWTAuth::getPayload($token)->toArray();

        return [
            "user_id" => $payload['user_id']
        ];
    }
}
