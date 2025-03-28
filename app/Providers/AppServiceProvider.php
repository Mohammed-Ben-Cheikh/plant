<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\data\OrderRepositoryData;
use App\Repositories\Contracts\OrderRepository;
use App\Repositories\data\PlantsRepositoryData;
use App\Repositories\Contracts\PlantsRepository;
use App\Repositories\data\CategoriesRepositoryData;
use App\Repositories\Contracts\CategoriesRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoriesRepository::class, CategoriesRepositoryData::class);
        $this->app->bind(PlantsRepository::class, PlantsRepositoryData::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryData::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }


}
