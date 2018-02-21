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

        // This will load routes file at yourproject/routes/web.durc.php
        // and prepend it with App\DURC\Controllers namespace
        $this->app['router']->group(['middleware' => 'web', 'namespace' => 'App\Http\Controllers'], function () {
            if ( file_exists( base_path( 'routes/web.durc.php' ) ) ) {
                require base_path( 'routes/web.durc.php' );
            }

            if ( file_exists( base_path( 'routes/durc_test.php' ) ) ) {
                require base_path( 'routes/durc_test.php' );
            }
        });
    }
    public function provides()
    {
        return ['command.DURC'];
    }


}
