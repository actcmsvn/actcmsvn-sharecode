<?php

namespace Actcmsvn\Sharecode;

use Actcmsvn\Sharecode\Commands\MigrateCheckCommand;
use Actcmsvn\Sharecode\Commands\NewVersion;
use Actcmsvn\Sharecode\Commands\NewVersionModule;
use Actcmsvn\Sharecode\Commands\VendorCleanUpCommand;
use Illuminate\Support\ServiceProvider;

class ActcmsvnSharecodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/actcmsvn_sharecode.php','actcmsvn_sharecode'
        );
        $this->commands([
            VendorCleanUpCommand::class,
            NewVersion::class,
            NewVersionModule::class,
            MigrateCheckCommand::class
        ]);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->publishFiles();

        include __DIR__ . '/routes.php';
    }

    public function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/Config/actcmsvn_sharecode.php' => config_path('actcmsvn_sharecode.php'),
        ]);

        $this->publishes([
            __DIR__ . '/Migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/actcmsvn-sharecode'),
        ]);

    }
}
