<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load WN Generator commands in dev mode (https://github.com/webNeat/lumen-generators)
        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
        }

        $this->app->register('Flipbox\LumenGenerator\LumenGeneratorServiceProvider');
    }
}