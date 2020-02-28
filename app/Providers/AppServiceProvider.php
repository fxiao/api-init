<?php

namespace App\Providers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'tags' => 'App\Models\Tag',
            'users' =>  'App\Models\User',
        ]);
        \Carbon\Carbon::setLocale('zh');
    }


    /**
     * Register any application services.
     */
    public function register()
    {
        // 注册mail
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');
            $app->configure('mail');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });

        // 处理dingo的findOrFail 问题
        // 或许可以放在  ApiExceptionServiceProvider 这样的地方
        app('api.exception')->register(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404);
        });

        // 开启 SQL 日志
        if (env('APP_DEBUG', false)) {
            \DB::listen(function (QueryExecuted $query) {
                $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
                $bindings = $query->connection->prepareBindings($query->bindings);
                $pdo = $query->connection->getPdo();

                (new Logger('sql'))
                    ->pushHandler(
                        (new StreamHandler(storage_path('logs/sql-'.date('Y-m-d').'.log'), Logger::DEBUG))
                            ->setFormatter(new LineFormatter(null, null, true, true))
                    )->info(vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings)));
            });
        }
    }
}
