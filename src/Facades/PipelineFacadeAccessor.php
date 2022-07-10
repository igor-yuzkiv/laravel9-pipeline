<?php

namespace Pipeline\Facades;

use Pipeline\Builder;
use Pipeline\Pipeline;
use Pipeline\Runners\PipelineRunner;


/**
 *
 */
class PipelineFacadeAccessor
{
    /**
     * @return Builder
     */
    public function buildPipeline(): Builder
    {
        return new Builder();
    }

    public function runPipeline(Pipeline $pipeline): void {
        (new PipelineRunner($pipeline))->run();
    }
}
