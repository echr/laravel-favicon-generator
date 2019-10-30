<?php

namespace Coderello\FaviconGenerator;

use Coderello\FaviconGenerator\Commands\FaviconDrop;
use Coderello\FaviconGenerator\Commands\FaviconGenerate;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FaviconManipulator::class, function () {
            return new FaviconManipulator(
                $this->app->make(RealFaviconGeneratorClient::class),
                $this->app->make(GuzzleClient::class),
                $this->app['config']['favicon-generator']
            );
        });

        $this->publishes([
            __DIR__.'/../config/favicon-generator.php' => config_path('favicon-generator.php'),
        ], 'favicon-generator-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/favicon-generator.php', 'favicon-generator'
        );

        $this->commands([
            FaviconDrop::class,
            FaviconGenerate::class,
        ]);
    }
}
