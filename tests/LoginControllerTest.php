<?php

namespace Laravue3\Stateless\Tests;

use Laravue3\Stateless\HasApiTokens;
use Laravue3\Stateless\Middleware\StatelessMiddleware;
use Laravue3\Stateless\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class LoginControllerTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        config(['stateless.userModel' => User::class]);
        config(['auth.providers.users.model' => User::class]);

    }

    public function test_login()
    {
        $this->withoutExceptionHandling();

        $this->loadLaravelMigrations();
        $this->artisan('migrate:refresh', ['--database' => 'testbench'])->run();


        Route::post('/api/login', '\Laravue3\Stateless\Controllers\LoginController@authenticate');
        $user = User::create([
            'name' => 'test',
            'email' => 'test@email.com',
            'password' => password_hash('123456', PASSWORD_BCRYPT)
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertOk();
        $this->assertTrue(\Auth::id() == $user->id);
    }

    // public function test_authenticate_with_cookie_when_route_is_protected()
    // {
    //     $this->loadLaravelMigrations();
    //     $this->artisan('migrate', ['--database' => 'testbench'])->run();

    //     Route::get('/foo', function () {
    //         return 'bar';
    //     })->middleware(\Illuminate\Cookie\Middleware\EncryptCookies::class)->middleware(\Laravue3\Stateless\Middleware\StatelessMiddleware::class);

    //     $user = User::create([
    //         'name' => 'a',
    //         'email' => 'a@a.com',
    //         'password' => password_hash('testbench', PASSWORD_BCRYPT),
    //     ]);
    //     $newAccessToken = $user->createToken('auth');

    //     config(['stateless.userModel' => User::class]);

    //     $response = $this->withCookie('tks', json_encode($newAccessToken))->get('/foo');
    //     $response->assertOk();
    // }
}

// class User2 extends Authenticatable
// {
//     use HasApiTokens;

//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//     ];
// }
