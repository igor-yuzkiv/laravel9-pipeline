<?php

declare(strict_types=1);

namespace Pipeline;

use Pipeline\Contracts\Runnable;
use Pipeline\Runners\PipelineRunner;
use Traversable;
use Pipeline\Contracts\Action;

/**
 *
 */
class Pipeline implements \IteratorAggregate
{
    /**
     * @var array<Action|Runnable>
     */
    private array $actions;

    /**
     *
     */
    public function __construct()
    {
        $this->actions = [];
    }

    /**
     * @param Runnable|Action $action
     * @return $this
     */
    public function addAction(Runnable|Action $action): self
    {
        $this->actions[] = $action;
        return $this;
    }


    public function run(): void
    {
        $runner = new PipelineRunner($this);
        $runner->run();
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->actions);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return (empty($this->actions));
    }
}
