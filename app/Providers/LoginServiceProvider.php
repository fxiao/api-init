<?php

namespace App\Providers;

use App\Guard\AuthGuard;
use Tymon\JWTAuth\Providers\LumenServiceProvider;

class LoginServiceProvider extends LumenServiceProvider
{
    protected function extendAuthGuard() {
        $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
            $guard = new AuthGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}
