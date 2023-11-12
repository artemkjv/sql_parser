<?php

namespace App\Providers;

use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\Article\DBArticleRepository;
use App\Repositories\Dump\DumpRepositoryInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        App::bind(DumpRepositoryInterface::class, function () {
            return new \App\Repositories\Dump\DBDumpRepository();
        });
        App::bind(ArticleRepositoryInterface::class, function () {
            return new DBArticleRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
