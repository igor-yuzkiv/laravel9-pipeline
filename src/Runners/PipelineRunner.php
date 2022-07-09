<?php

declare(strict_types=1);

namespace Pipeline\Runners;

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


    public function run(): void
    {
        foreach ($this->pipeline as $action) {
            $runner = new ActionRunner($action);

            if (is_a($this->response, Response::class)) {
                $runner->withResponse($this->response);
            }

            $this->response = $runner->run();

            if ($this->response->getStatusCode() === ResponseStatusCode::BREAK) {
                break;
            }
        }
    }
}
