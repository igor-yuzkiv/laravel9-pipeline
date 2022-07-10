<?php

namespace Pipeline\Providers;

use Illuminate\Support\ServiceProvider;
use Pipeline\Facades\PipelineFacadeAccessor;

/**
 *
 */
class PipelineServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(PipelineFacadeAccessor::class, function () {
            return new PipelineFacadeAccessor();
        });
    }
}

