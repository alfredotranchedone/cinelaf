<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     * @throws \Exception
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        /* Controlla se la configurazione è in cache */
        if( $app->configurationIsCached() ){
            throw new \Exception('Configuration is cached! Any variable in phpunit.xml did not take effect. Run `$ php artisan config:clear`!');
        }

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
