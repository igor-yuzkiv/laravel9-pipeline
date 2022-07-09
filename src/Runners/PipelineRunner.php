<?php

declare(strict_types=1);

namespace Pipeline\Runners;

use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;
use Pipeline\Contracts\Runnable;
use Pipeline\Enums\ResponseStatusCode;
use Pipeline\Exceptions\PipelineEmptyException;
use Pipeline\Pipeline;

/**
 *
 */
class PipelineRunner implements Runnable
{

    /**
     * @var Response|null
     */
    private ?Response $response = null;

    /**
     * @param Pipeline $pipeline
     * @throws PipelineEmptyException
     */
    public function __construct(
        private readonly Pipeline $pipeline
    )
    {
        if ($this->pipeline->isEmpty()) {
            throw new PipelineEmptyException();
        }
    }

    /**
     * @return void
     */
    public function run(): void
    {
        /**
         * @var Action $action
         */
        foreach ($this->pipeline as $action) {
            if (is_a($this->response, Response::class)) {
                $action->withResponse($this->response);
            }

            $this->response = $this->runAction($action);

            dump($this->response->toArray());

            if ($this->response->getStatusCode() === ResponseStatusCode::BREAK) {
                break;
            }
        }
    }

    /**
     * @param Action $action
     * @return Response
     */
    private function runAction(Action $action): Response
    {
        return (new ActionRunner($action))->run();
    }
}
