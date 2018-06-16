<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Token;

class AuthenticatePush
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(
            !($appID = $request->header('X-APP-ID')) ||
            !($secret = $request->bearerToken()) ||
            !$this->validateSecretWithApp($secret, $appID)
          )
        {
            return response()->json(['meta' => generate_meta('failure','FORBIDDEN')], 401);
        }

        return $next($request);
    }

    /**
     * Make sure that both secret and app_id are valid
     *
     * @param string $secret
     * @param string $appID
     * @return boolean
     */
    public function validateSecretWithApp($secret, $appID)
    {
        return Token::where('secret', $secret)->where('app_id', $appID)->count();
    }
}
