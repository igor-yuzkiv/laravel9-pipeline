<?php

namespace Pipeline;

use Pipeline\Contracts\Action;
use Pipeline\Contracts\Runnable;
use Pipeline\Exceptions\InvalidPipelineActionException;
use Pipeline\Job\QueueableClosure;

/**
 *
 */
final class ValidateAction
{
    /**
     * @param $action
     * @return bool
     */
    public static function isValid($action): bool
    {
        return (
            is_a($action, Action::class) ||
            is_a($action, Runnable::class) ||
            is_a($action, QueueableClosure::class) ||
            is_callable($action)
        );
    }

    /**
     * @param $action
     * @return void
     * @throws InvalidPipelineActionException
     */
    public static function throwIfInvalid($action): void
    {
        if (!self::isValid($action)) {
            throw new InvalidPipelineActionException();
        }
    }
}
