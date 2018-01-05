<?php

namespace RealPage\JsonApi\Lumen;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use RealPage\JsonApi\MediaTypeGuard;
use RealPage\JsonApi\EncoderService;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {
        $this->app->configure('json-api');

        $this->mergeConfigFrom(__DIR__ . '/../../config/json-api.php', 'json-api');

        $this->app->bind(MediaTypeGuard::class, function ($app) {
            return new MediaTypeGuard(config('json-api.media-type'), config('json-api.accept-header-policy'));
        });

        $this->app->bind(EncoderService::class, function ($app) {
            return new EncoderService(config('json-api'));
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [MediaTypeGuard::class, EncoderService::class];
    }
}
