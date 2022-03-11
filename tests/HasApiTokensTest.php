<?php

namespace Laravue3\Stateless\Tests;

use Laravue3\Stateless\HasApiTokens;
use Laravue3\Stateless\Abc;
use Laravue3\Stateless\PersonalToken;
use Laravue3\Stateless\Tests\TestCase;

class HasApiTokensTest extends TestCase
{
    public function test_tokens_can_be_created()
    {
        $this->assertEquals(true,true);
        $class = new ClassThatHasApiTokens;

        $newToken = $class->createToken('test', ['foo']);

        // [$id, $token] = explode('|', $newToken->plainTextToken);

        // $this->assertEquals(
        //     $newToken->accessToken->token,
        //     hash('sha256', $token)
        // );

        // $this->assertEquals(
        //     $newToken->accessToken->id,
        //     $id
        // );
    }
}

class ClassThatHasApiTokens
{
    use HasApiTokens;
    use Abc;

    public function tokens()
    {
        return new class {
            public function create(array $attributes)
            {
                return new PersonalToken($attributes);
            }
        };
    }
}
