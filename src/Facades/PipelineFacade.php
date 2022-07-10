<?php

namespace Pipeline\Facades;

use Illuminate\Support\Facades\Facade;
use Pipeline\Builder;
use Pipeline\Pipeline;

/**
 * @method static Builder buildPipeline()
 * @method static void runPipeline(Pipeline $pipeline)
 */
class PipelineFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return PipelineFacadeAccessor::class;
    }
}
