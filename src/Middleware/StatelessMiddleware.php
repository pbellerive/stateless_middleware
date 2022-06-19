<?php

namespace Laravue3\Stateless\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravue3\Stateless\PersonalToken;

class StatelessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //if for some reason the user is already authenticated.continue.
        if (Auth::user()) {
            return $next($request);
        }

        // read secure cookie.
        $tksCookie = $request->cookie('tks');
        $tks = json_decode($tksCookie);

        var_dump('**************************');
        var_dump($tksCookie);
        // got a token ?
        if (!is_null($tks)) {
            $personalToken = PersonalToken::findToken($tks->plainTextToken);

            if ($personalToken) {
                $personalToken->forceFill(['last_used_at' => now()])->save();
                Auth::login($personalToken->user);
                return $next($request);
            }
        }

        abort(401);
    }
}
