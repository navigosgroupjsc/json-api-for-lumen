<?php

namespace RealPage\JsonApi\Lumen;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '../config/json-api.php' => config_path('json-api.php'),
        ]);
    }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '../config/json-api.php', 'json-api');
    }
}
