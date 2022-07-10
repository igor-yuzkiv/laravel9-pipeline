<?php

namespace Pipeline\Runners;


use Pipeline\ActionResponse;
use Pipeline\Contracts\ConditionAction;
use Pipeline\Contracts\Response;
use Pipeline\Contracts\Runnable;
use Pipeline\Enums\ResponseStatusCode;
use Pipeline\Exceptions\PipelineEmptyException;
use Pipeline\Pipeline;
use Pipeline\ValidateAction;

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

        $this->response = (new ActionResponse())
            ->withStatusCode(ResponseStatusCode::SUCCESS);
    }

    /**
     * @return void
     * @throws \Pipeline\Exceptions\InvalidPipelineActionException
     */
    public function run(): void
    {
        foreach ($this->pipeline as $action) {
            if ($this->response->getStatusCode() === ResponseStatusCode::BREAK) {
                $this->runBreakAction();
                break;
            }

            $runner = ActionRunner::of($action);

            $isCondition = $this->runConditionAction($action);
            if ($isCondition) {
                continue;
            }

            $this->response = $runner
                ->withResponse($this->response)
                ->run();
        }

        $this->runFinishedAction();
    }

    /**
     * @param $action
     * @return void
     */
    private function runConditionAction($action): bool
    {
        if (!is_a($action, ConditionAction::class)) {
            return false;
        }

        if ($this->response->getStatusCode() == $action->runOn()) {
            $action
                ->withResponse($this->response)
                ->run();
            $this->response = $action->getResponse();
        }

        return true;
    }

    /**
     * @return void
     * @throws \Pipeline\Exceptions\InvalidPipelineActionException
     */
    private function runFinishedAction(): void
    {
        $action = $this->pipeline->getFinishedAction();

        if (!ValidateAction::isValid($action)) {
            return;
        }

        $this->response = ActionRunner::of($action)
            ->withResponse($this->response)
            ->run();
    }

    /**
     * @return void
     * @throws \Pipeline\Exceptions\InvalidPipelineActionException
     */
    private function runBreakAction(): void
    {
        $action = $this->pipeline->getBreakAction();
        if (!ValidateAction::isValid($action)) {
            return;
        }

        $this->response = ActionRunner::of($action)
            ->withResponse($this->response)
            ->run();
    }
}
