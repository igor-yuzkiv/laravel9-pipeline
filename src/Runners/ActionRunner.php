<?php

declare(strict_types=1);

namespace Pipeline\Runners;

use Pipeline\ActionResponse;
use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;
use Pipeline\Contracts\Runnable;
use Pipeline\Enums\ResponseStatusCode;
use Pipeline\Exceptions\InvalidPipelineAction;

/**
 *
 */
class ActionRunner implements Runnable
{
    /**
     * @var Response
     */
    private Response $outputResponse;

    /**
     * @var Response|null
     */
    private ?Response $inputResponse = null;

    /**
     * @param Action|Runnable $action
     */
    public function __construct(
        private readonly Action|Runnable $action
    )
    {
        $this->outputResponse = new ActionResponse();
    }

    /**
     * @param Response $response
     * @return $this
     */
    public function withResponse(Response $response): self
    {
        $this->inputResponse = $response;
        return $this;
    }

    /**
     * @return Response
     * @throws InvalidPipelineAction
     */
    public function run(): Response
    {
        if ($this->isPipelineAction()) {
            $this->runPipelineAction();
        } else if ($this->isStatelessAction()) {
            $this->runStatelessAction();
        } else {
            throw new InvalidPipelineAction();
        }

        return $this->outputResponse;
    }

    /**
     * @return bool
     */
    private function isPipelineAction(): bool
    {
        return is_a($this->action, Action::class);
    }

    /**
     * @return bool
     */
    private function isStatelessAction(): bool
    {
        return is_a($this->action, Runnable::class);
    }

    /**
     * @return void
     */
    private function runPipelineAction(): void
    {
        try {
            if (!empty($this->inputResponse)) {
                $this->action->withResponse($this->inputResponse);
            }

            $this->action->run();

            $this->outputResponse = $this->action->getResponse();
        } catch (\Exception $exception) {
            $this->setFailedResponse($exception->getMessage());
        }
    }

    /**
     * @return void
     */
    private function runStatelessAction(): void
    {
        try {
            $response = $this->action->run();
            $this->outputResponse->withStatusCode(ResponseStatusCode::SUCCESS);
            $this->outputResponse->withResponse(['stateless' => $response ?? null]);
        } catch (\Exception $exception) {
            $this->setFailedResponse($exception->getMessage());
        }
    }

    /**
     * @param string $message
     * @return void
     */
    private function setFailedResponse(string $message): void
    {
        $this->outputResponse->withStatusCode(ResponseStatusCode::FAILED);
        $this->outputResponse->withMessage($message);
    }
}
