<?php

declare(strict_types=1);

namespace Pipeline;

use Pipeline\Contracts\Response;
use Pipeline\Enums\ResponseStatusCode;

/**
 *
 */
class ActionResponse implements Response
{
    /**
     * @var string
     */
    private string $message;

    /**
     * @param ResponseStatusCode $statusCode
     * @param array $response
     */
    public function __construct(
        private readonly ResponseStatusCode $statusCode,
        private readonly array              $response
    )
    {

    }

    /**
     * @return ResponseStatusCode
     */
    public function getStatusCode(): ResponseStatusCode
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function withMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status'   => $this->statusCode->name,
            'response' => $this->response,
        ];
    }
}
