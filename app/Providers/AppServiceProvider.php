<?php

namespace App\Providers;

use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(ConnectionEstablished::class, function (ConnectionEstablished $event): void {
            if ($event->connection->getDriverName() === 'sqlite') {
                $event->connection->getPdo()->sqliteCreateFunction(
                    'unicode_lower',
                    static fn (?string $value): ?string => $value === null ? null : mb_strtolower($value, 'UTF-8'),
                    1,
                    PDO::SQLITE_DETERMINISTIC,
                );
            }
        });

        Vite::prefetch(concurrency: 3);
    }
}
