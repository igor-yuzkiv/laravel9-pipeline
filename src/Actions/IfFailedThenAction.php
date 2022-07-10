<?php
declare(strict_types=1);

namespace Pipeline\Actions;

use Pipeline\Contracts\ConditionAction;
use Pipeline\Contracts\Runnable;
use Pipeline\Enums\ResponseStatusCode;
use Pipeline\Job\QueueableClosure;
use Pipeline\PipelineAction;
use Pipeline\Runners\ActionRunner;

/**
 *
 */
class IfFailedThenAction extends PipelineAction implements ConditionAction
{
    /**
     * @param PipelineAction|Runnable|QueueableClosure|callable $action
     */
    public function __construct(
        private readonly mixed $action
    )
    {

    }

    /**
     * @return void
     * @throws \Pipeline\Exceptions\InvalidPipelineActionException
     */
    public function run():void
    {
        $this->outputResponse = ActionRunner::of($this->action)
            ->withResponse($this->inputResponse)
            ->run();
    }

    /**
     * @return ResponseStatusCode
     */
    public function runOn(): ResponseStatusCode
    {
        return ResponseStatusCode::FAILED;
    }
}
