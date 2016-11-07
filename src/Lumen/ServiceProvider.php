<?php

namespace RealPage\JsonApi\Lumen;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use RealPage\JsonApi\MediaTypeGuard;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/json-api.php', 'json-api');

        $this->app->bind(MediaTypeGuard::class, function ($app) {
            return new MediaTypeGuard(config('json-api.media-type'));
        });
    }
}
