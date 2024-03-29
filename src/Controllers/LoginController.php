<?php

namespace Laravue3\Stateless\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Laravue3\Stateless\PersonalToken;

class LoginController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $token = $user->createToken('auth');
            $cookie = cookie('tks', json_encode($token));


            if (config('stateless.resourceClass')) {
                $resource = config('stateless.resourceClass');
                $user = new $resource($user);
            }

            return response([
                'user' => $user
            ], 200)->withCookie($cookie);
        }

        return response('', 401);
    }

    public function getTokenWithPassword(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth');
            // $cookie = cookie('tks', json_encode($token));

            if (config('stateless.resourceClass')) {
                $resource = config('stateless.resourceClass');
                $user = new $resource($user);
            }

            return response([
                'tks' => $token,
                'user' => $user
            ], 200);
        }

        return response('', 401);

    }

    public function logout(Request $request)
    {
        $tksCookie = $request->cookie('tks');
        $tks = json_decode($tksCookie);

        if (is_null($tks)) {
            abort(401);
        }

        $personalToken = PersonalToken::findToken($tks->plainTextToken);
        $personalToken->delete();
    }

    public function checkLogin()
    {
        $user = Auth::user();
        if (config('stateless.resourceClass')) {
            $resource = config('stateless.resourceClass');
            $user = new $resource($user);
        }

        return response($user);
    }
}
