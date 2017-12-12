<?php

namespace CareSet\DURC;

use Illuminate\Support\ServiceProvider;

class DURCServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function register()
    {
            $this->commands([
                DURCCommand::class,
                DURCMineCommand::class,
                DURCWriteCommand::class,
            ]);
    }
    public function provides()
    {
        return ['command.DURC'];
    }


}
