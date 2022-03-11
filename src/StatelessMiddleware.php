<?php

namespace Laravue3\Stateless;

use Closure;
use Illuminate\Http\Request;

// use Laravel\Sanctum\PersonalAccessToken;

class StatelessTokenAuthenticate
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
        $tksCookie = $request->cookie('tks');
        $tks = json_decode($tksCookie);

        if (\Auth::user()) {
            return $next($request);
        }

        if (!is_null($tks)) {
            // $personalToken = PersonalAccessToken::findToken($tks->plainTextToken);

            // if ($personalToken) {
            //     $personalToken->forceFill(['last_used_at' => now()])->save();
            //     \Auth::login(\App\Users\User::find($personalToken->tokenable_id));
            //     return $next($request);
            // }
        }

        abort(401);
    }
}
