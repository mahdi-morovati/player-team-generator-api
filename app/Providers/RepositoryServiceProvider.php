<?php

namespace App\Providers;

use App\Repositories\Player\PlayerRepository;
use App\Repositories\Player\PlayerRepositoryInterface;
use App\Repositories\Player\PlayerSkillRepository;
use App\Repositories\Player\PlayerSkillRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PlayerRepositoryInterface::class, PlayerRepository::class);
        $this->app->bind(PlayerSkillRepositoryInterface::class, PlayerSkillRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
