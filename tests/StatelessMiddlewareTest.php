<?php

namespace Laravue3\Stateless\Tests;

use Laravue3\Stateless\HasApiTokens;
use Laravue3\Stateless\Middleware\StatelessMiddleware;
use Laravue3\Stateless\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class StatelessMiddlewareTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
//
}

public function test_actingas_when_route_is_protected()
{
    $this->withoutExceptionHandling();

    Route::get('/foo', function () {
        return 'bar';
    })->middleware(\Laravue3\Stateless\Middleware\StatelessMiddleware::class);

    app('auth')->setUser(new User());
    $response = $this->get('/foo');
    $response->assertOk();
}

public function test_authenticate_with_cookie_when_route_is_protected()
{
        $this->loadLaravelMigrations();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        Route::get('/foo', function () {
            return 'bar';
        })->middleware(\Illuminate\Cookie\Middleware\EncryptCookies::class)->middleware(\Laravue3\Stateless\Middleware\StatelessMiddleware::class);

        $user = User::create([
            'name' => 'a',
            'email' => 'a@a.com',
            'password' => password_hash('testbench', PASSWORD_BCRYPT),
        ]);
        $newAccessToken = $user->createToken('auth');

        config(['stateless.userModel' => User::class]);

        $response = $this->withCookie('tks', json_encode($newAccessToken))->get('/foo');
        $response->assertOk();
    }
}

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
