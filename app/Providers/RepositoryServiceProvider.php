<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings.
     * Jaise jaise repositories bante jayenge, yahan bind karo:
     *
     * $this->app->bind(
     *     \App\Repositories\Contracts\ProductRepositoryInterface::class,
     *     \App\Repositories\ProductRepository::class,
     * );
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
