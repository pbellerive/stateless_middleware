<?php

namespace Laravue3\Authentication;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
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
    }
}
