<?php

namespace App\Realtime\Auth;

use App\Models\Token;
use \Thruway\Authentication\AbstractAuthProviderClient;


class TokenAuth extends AbstractAuthProviderClient{

    public function getMethodName()
    {
        return 'token';
    }

    public function processAuthenticate($signature, $extra = null)
    {
        $token = Token::where('app_id', $signature)->first();
        if($token){
            // This should be ['SUCCESS'] only but a function that handle it has second parameter with no default value
            // I'm sending it's second parameter that supports to be default and dynamic
            return ['SUCCESS', new \stdClass()];
        }

        return ['FAILURE'];
    }

}