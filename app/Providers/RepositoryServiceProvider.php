<?php

namespace App\Providers;

use App\Contracts\Repository\GameRepositoryInterface;
use App\Contracts\Repository\TeamRepositoryInterface;
use App\Repositories\Eloquent\GameRepository;
use App\Repositories\Eloquent\TeamRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
