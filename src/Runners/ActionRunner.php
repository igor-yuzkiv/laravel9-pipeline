<?php

namespace Pipeline\Runners;

use Pipeline\Contracts\Runnable;
use Pipeline\Job\QueueableClosure;
use Pipeline\ActionResponse;
use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;
use Pipeline\Enums\ResponseStatusCode;
use Pipeline\Exceptions\InvalidPipelineActionException;

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
     * @param Action|Runnable|callable|QueueableClosure $action
     */
    public function __construct(
        private readonly mixed $action
    )
    {
        $this->outputResponse = new ActionResponse();
    }

    /**
     * @param $action
     * @return ActionRunner
     */
    public static function of($action): self
    {
        return new self($action);
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
     * @throws InvalidPipelineActionException
     */
    public function run(): Response
    {
        try {
            if ($this->isPipelineAction()) {
                $this->runPipelineAction();
            } else if ($this->isStatelessAction()) {
                $this->runStatelessAction();
            } else if ($this->isClosureAction()) {
                $this->runClosureAction();
            } else {
                throw new InvalidPipelineActionException();
            }
        } catch (InvalidPipelineActionException $exception) {
            throw  $exception;
        } catch (\Exception $exception) {
            $this->setFailedResponse($exception->getMessage());
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
     * @return bool
     */
    private function isClosureAction(): bool
    {
        return is_a($this->action, QueueableClosure::class) || is_callable($this->action);
    }

    /**
     * @return void
     */
    private function runPipelineAction(): void
    {
        if (!empty($this->inputResponse)) {
            $this->action->withResponse($this->inputResponse);
        }

        $this->action->run();
        $this->outputResponse = $this->action->getResponse();
    }

    /**
     * @return void
     */
    private function runStatelessAction(): void
    {
        $response = $this->action->run();
        $this->outputResponse->withStatusCode(ResponseStatusCode::SUCCESS);
        $this->outputResponse->withResponse(['stateless' => $response ?? null]);
    }

    /**
     * @return void
     */
    private function runClosureAction(): void
    {
        if (is_a($this->action, QueueableClosure::class)) {
            $response = $this->action->handle();
        } else {
            $response = call_user_func($this->action, $this->inputResponse);
        }


        if (is_a($response, Response::class)) {
            $this->outputResponse = $response;
        } else {
            $this->outputResponse->withStatusCode(ResponseStatusCode::SUCCESS);
            if (is_array($response)) {
                $this->outputResponse->withResponse($response);
            }
        }
    }

    /**
     * @param string $message
     * @return void
     */
    private function setFailedResponse(string $message): void
    {
        $this->outputResponse
            ->withStatusCode(ResponseStatusCode::FAILED)
            ->withMessage($message)
            ->withResponse([]);
    }
}
