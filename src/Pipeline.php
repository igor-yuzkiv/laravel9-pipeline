<?php
declare(strict_types=1);

namespace Pipeline;

use Pipeline\Contracts\Runnable;
use Pipeline\Contracts\Action;
use Pipeline\Exceptions\InvalidPipelineActionException;
use Pipeline\Job\QueueableClosure;
use Traversable;

/**
 *
 */
final class Pipeline implements \IteratorAggregate
{
    /**
     * @var Runnable[]|QueueableClosure[]|Action[]|callable[]
     */
    public array $actions = [];

    /**
     * @var Action|Runnable|\Closure|QueueableClosure|null
     */
    private mixed $finishedAction = null;

    /**
     * @var Action|Runnable|callable|QueueableClosure|null
     */
    private mixed $breakAction = null;

    /**
     * @return Runnable[]|QueueableClosure[]|Action[]|callable[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return (empty($this->actions));
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->actions);
    }

    /**
     * @return \Closure|mixed|Action|Runnable|QueueableClosure|null
     */
    public function getFinishedAction(): mixed
    {
        return $this->finishedAction;
    }

    /**
     * @param $finishedAction
     * @return void
     * @throws InvalidPipelineActionException
     */
    public function setFinishedAction($finishedAction): void
    {
        ValidateAction::throwIfInvalid($finishedAction);
        $this->finishedAction = $finishedAction;
    }

    /**
     * @return callable|Action|Runnable|QueueableClosure|null
     */
    public function getBreakAction(): mixed
    {
        return $this->breakAction;
    }

    /**
     * @param null $breakAction
     */
    public function setBreakAction($breakAction): self
    {
        $this->breakAction = $breakAction;
        return $this;
    }
}
