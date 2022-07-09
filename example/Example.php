<?php

namespace Example;

use Example\Actions\Action1;
use Example\Actions\Action2;
use Example\Actions\Action3;
use Pipeline\Pipeline;
use Pipeline\Runners\PipelineRunner;

class Example
{
    public static function main()
    {
        $pipeline = new Pipeline();

        $pipeline
            ->addAction((new Action1())->withArguments(test: 1))
            ->addAction(new Action2())
            ->addAction(new Action3())
            ->run();
    }
}
