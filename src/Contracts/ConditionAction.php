<?php
declare(strict_types=1);

namespace Pipeline\Contracts;

use Pipeline\Enums\ResponseStatusCode;

interface ConditionAction extends Action
{
    public function runOn(): ResponseStatusCode;
}
