<?php

namespace Example\Actions;

use Pipeline\ActionResponse;
use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;
use Pipeline\Enums\ResponseStatusCode;
use Pipeline\Exceptions\ActionFailedException;
use function PHPUnit\Framework\isEmpty;

/**
 *
 */
class Action1 implements Action
{
    /**
     * @var Response|null
     */
    private readonly ?Response $inputResponse;

    /**
     * @var Response
     */
    private Response $outputResponse;

    /**
     * @var array
     */
    private array $arguments;

    /**
     * @param Response $response
     * @return Action
     */
    public function withResponse(Response $response): Action
    {
        $this->inputResponse = $response;
        return $this;
    }

    /**
     * @param mixed ...$namedArguments
     * @return Action
     */
    public function withArguments(mixed ...$namedArguments): Action
    {
        $this->arguments = $namedArguments;
        return $this;
    }

    /**
     * @return Response
     * @throws ActionFailedException
     */
    public function run(): Response
    {
        throw new ActionFailedException("Action 1 exception!");
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->outputResponse;
    }
}

