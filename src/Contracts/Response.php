<?php

declare(strict_types=1);

namespace Pipeline\Contracts;

use Pipeline\Enums\ResponseStatusCode;

/**
 *
 */
interface Response
{
    /**
     * @return ResponseStatusCode
     */
    public function getStatusCode(): ResponseStatusCode;

    /**
     * @return array
     */
    public function getResponse(): array;

    /**
     * @return array
     */
    public function toArray(): array;
}
