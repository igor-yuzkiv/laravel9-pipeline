<?php
declare(strict_types=1);

namespace Pipeline;


use Illuminate\Foundation\Bus\PendingDispatch;
use Pipeline\Actions\IfFailedBreak;
use Pipeline\Actions\IfFailedThenAction;
use Pipeline\Exceptions\InvalidPipelineActionException;
use Pipeline\Job\PipelineJob;
use Pipeline\Job\QueueableClosure;
use Pipeline\Runners\PipelineRunner;

/**
 *
 */
final class Builder
{
    /**
     * @var Pipeline
     */
    private Pipeline $pipeline;

    /**
     *
     */
    public function __construct()
    {
        $this->pipeline = new Pipeline();
    }

    /**
     * @param array $actions
     * @return $this
     * @throws InvalidPipelineActionException
     */
    public function of(array $actions): self
    {
        $self = new self();

        foreach ($actions as $action) {
            $self->addAction($action);
        }

        return $self;
    }

    /**
     * @param $action
     * @return $this
     * @throws InvalidPipelineActionException
     */
    public function addAction($action): self
    {
        ValidateAction::throwIfInvalid($action);
        $this->pipeline->actions[] = $action;
        return $this;
    }

    /**
     * @return Pipeline
     */
    public function getPipeline(): Pipeline
    {
        return $this->pipeline;
    }

    /**
     * @return $this
     * @throws InvalidPipelineActionException
     */
    public function breakIfFailed(): self
    {
        $this->addAction(
            new IfFailedBreak()
        );

        return $this;
    }

    /**
     * @param mixed $action
     * @return $this
     * @throws InvalidPipelineActionException
     */
    public function thenIfFailed(mixed $action): self
    {
        $this->addAction(
            new IfFailedThenAction($action)
        );

        return $this;
    }

    /**
     * @param $action
     * @return $this
     * @throws InvalidPipelineActionException
     */
    public function finished($action): self
    {
        $this->pipeline->setFinishedAction($action);
        return $this;
    }

    /**
     * @param $action
     * @return $this
     */
    public function onBreak($action): self
    {
        $this->pipeline->setBreakAction($action);
        return $this;
    }

    /**
     * @return void
     * @throws Exceptions\PipelineEmptyException
     * @throws InvalidPipelineActionException
     */
    public function run(): void
    {
        $runner = new PipelineRunner($this->pipeline);
        $runner->run();
    }

    /**
     * @return PendingDispatch
     * @throws \Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException
     */
    public function dispatch(): PendingDispatch
    {
        $pipeline = new Pipeline();
        foreach ($this->pipeline->getActions() as $action) {
            if (is_callable($action)) {
                $action = new QueueableClosure($action);
            }
            $this->addAction($action);
        }
        return dispatch(new PipelineJob($pipeline));
    }
}
