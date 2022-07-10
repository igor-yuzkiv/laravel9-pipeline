<?php

namespace Pipeline;

use Pipeline\Contracts\Response;
use Pipeline\Enums\ResponseStatusCode;

/**
 *
 */
class ActionResponse implements Response
{
    /**
     * @var ResponseStatusCode
     */
    private ResponseStatusCode $statusCode;

    /**
     * @var array
     */
    private array $response;

    /**
     * @var string
     */
    private string $message;

    /**
     * @return static
     */
    public static function break(): self
    {
        $self = new self();
        $self->withStatusCode(ResponseStatusCode::BREAK);
        return $self;
    }

    /**
     * @param string $message
     * @param array $response
     * @return static
     */
    public static function failed(string $message, array $response = []): self
    {
        $self = new self();
        $self->withStatusCode(ResponseStatusCode::FAILED)
            ->withMessage($message)
            ->withResponse($response);

        return $self;
    }

    /**
     * @param ResponseStatusCode $statusCode
     * @return $this
     */
    public function withStatusCode(ResponseStatusCode $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param array $response
     * @return Response
     */
    public function withResponse(array $response): self
    {
        $this->response = $response;
        return $this;
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
            'status'   => $this->statusCode,
            'response' => $this->response,
        ];
    }
}
