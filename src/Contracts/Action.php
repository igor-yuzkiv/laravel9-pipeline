<?php

declare(strict_types=1);

namespace Pipeline\Contracts;

/**
 *
 */
interface Action extends Runnable
{
    /**
     * @param Response $response
     * @return $this
     */
    public function withResponse(Response $response): self;

    /**
     * @param mixed ...$namedArguments
     * @return $this
     */
    public function withArguments(mixed ...$namedArguments): self;

    /**
     * @return Response
     */
    public function getResponse(): Response;
}
