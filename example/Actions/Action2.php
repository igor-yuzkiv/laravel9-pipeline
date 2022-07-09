<?php

namespace Example\Actions;

use Pipeline\ActionResponse;
use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;
use Pipeline\Enums\ResponseStatusCode;

/**
 *
 */
class Action2 implements Action
{
    /**
     * @var Response
     */
    private readonly Response $inputResponse;

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
     * @param ...$namedArguments
     * @return Action
     */
    public function withArguments(...$namedArguments): Action
    {
        $this->arguments = $namedArguments ?? [];
        return $this;
    }

    /**
     * @return mixed|void
     */
    public function run()
    {
        $this->outputResponse = new ActionResponse(
            ResponseStatusCode::SUCCESS, [
                'inputResponse' => $this->inputResponse ?? null,
                'arguments'     => $this->arguments ?? null,
            ]
        );
        $this->outputResponse->withMessage(static::class);
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->outputResponse;
    }
}
