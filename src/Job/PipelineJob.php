<?php

namespace Pipeline\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pipeline\Facades\PipelineFacade;
use Pipeline\Pipeline;
use Pipeline\Runners\PipelineRunner;

/**
 *
 */
final class PipelineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @param Pipeline $pipeline
     */
    public function __construct(private readonly Pipeline $pipeline)
    {

    }

    /**
     * @return void
     */
    public function handle()
    {
        PipelineFacade::runPipeline($this->pipeline);
    }
}
