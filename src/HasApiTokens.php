<?php

namespace Laravue3\Stateless;

use Illuminate\Support\Str;

trait HasApiTokens
{
    /**
     * The access token the user is using for the current request.
     */
    // protected $accessToken;

    /**
     * Get the access tokens that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tokens()
    {
        return $this->hasMany(PersonalToken::class);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }

    // /**
    //  * Get the access token currently associated with the user.
    //  *
    //  * @return \Laravel\Sanctum\Contracts\HasAbilities
    //  */
    // public function currentAccessToken()
    // {
    //     return $this->accessToken;
    // }

    // /**
    //  * Set the current access token for the user.
    //  *
    //  * @param  \Laravel\Sanctum\Contracts\HasAbilities  $accessToken
    //  * @return $this
    //  */
    // public function withAccessToken($accessToken)
    // {
    //     $this->accessToken = $accessToken;

    //     return $this;
    // }
}
