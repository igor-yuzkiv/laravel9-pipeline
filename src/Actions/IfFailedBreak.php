<?php
declare(strict_types=1);

namespace Pipeline\Actions;

use Pipeline\PipelineAction;
use Pipeline\ActionResponse;

/**
 *
 */
class IfFailedBreak extends PipelineAction
{
    /**
     * @return void
     */
    public function run(): void
    {
        $this->outputResponse = ActionResponse::break();
    }
}
