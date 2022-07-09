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
     * @param ResponseStatusCode $statusCode
     * @return $this
     */
    public function withStatusCode(ResponseStatusCode $statusCode): self;

    /**
     * @param string $message
     * @return $this
     */
    public function withMessage(string $message): self;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param array $response
     * @return $this
     */
    public function withResponse(array $response): self;

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
