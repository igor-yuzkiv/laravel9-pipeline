<?php

declare(strict_types=1);

namespace Pipeline;

use Traversable;
use Pipeline\Contracts\Action;

/**
 *
 */
class Pipeline implements \IteratorAggregate
{
    /**
     * @var array<Action>
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
     * @return bool
     */
    public function isEmpty(): bool
    {
        return (empty($this->actions));
    }

    /**
     * @param Action $action
     * @return $this
     */
    public function addAction(Action $action): self
    {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->actions);
    }
}
