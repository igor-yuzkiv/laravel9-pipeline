<?php

namespace Pipeline;

use Pipeline\Contracts\Action;
use Pipeline\Contracts\Response;

/**
 *
 */
abstract class PipelineAction implements Action
{
    /**
     * @var Response
     */
    protected Response $inputResponse;

    /**
     * @var Response
     */
    protected Response $outputResponse;

    /**
     * @var array
     */
    protected array $arguments;

    /**
     * @param Response $response
     * @return $this
     */
    public function withResponse(Response $response): self
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
        $this->arguments = $namedArguments;
        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->outputResponse;
    }
}
