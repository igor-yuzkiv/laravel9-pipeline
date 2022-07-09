<?php

declare(strict_types=1);

namespace Pipeline\Runners;

use Pipeline\ActionResponse;
use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;
use Pipeline\Contracts\Runnable;
use Pipeline\Enums\ResponseStatusCode;

/**
 *
 */
class ActionRunner implements Runnable
{
    /**
     * @param Action $action
     */
    public function __construct(
        private readonly Action $action
    )
    {

    }

    /**
     * @return Response
     */
    public function run(): Response
    {
        try {
            $this->action->run();
            return $this->action->getResponse();
        } catch (\Exception $exception) {
            $response = new ActionResponse(ResponseStatusCode::FAILED, []);
            $response->withMessage($exception->getMessage());
            return $response;
        }
    }
}
