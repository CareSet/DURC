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
        $this->publishes([
            __DIR__.'/../assets/js' => public_path('js'),
            __DIR__.'/../assets/css' => public_path('css'),
        ], 'public');
    }

    public function register()
    {
        // Load the durc config file and merge it with the user's
        $this->mergeConfigFrom( base_path( 'vendor/careset/durc/config/durc.php' ), 'durc' );

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

            if ( config( 'durc.use_durctest_route' ) == true  &&
                file_exists( base_path( 'routes/durc_test.php' ) ) ) {
                require base_path( 'routes/durc_test.php' );
            }
        });
    }
    public function provides()
    {
        return ['command.DURC'];
    }


}
