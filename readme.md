# Example
```php

class StateLassAction implements \Pipeline\Contracts\Runnable
{
    public function __construct() {
    
    }

    public function run(){
        // TODO: Implement run() method.
    }
}

class PipeLineTestAction extends \Pipeline\PipelineAction {
    public function run(){
        $this->outputResponse = $this->inputResponsel;
        // TODO: Implement run() method.
    }
}

use Pipeline\ActionResponse;
use Pipeline\Contracts\Response;
use Pipeline\Facades\PipelineFacade;

PipelineFacade::buildPipeline()
    ->addAction(function () {
        dump(1);
        return ['previous' => 1];
    })
    ->thenIfFailed(
        function (Response $prevResponse) {
            dump("=====================================");
            dump("then if failed");
            dump($prevResponse->toArray());
            return ActionResponse::break();
        }
    )
    ->addAction(function (Response $prevResponse) {
        dump("=====================================");
        dump(2);
        dump($prevResponse->toArray());
        throw new \Exception("Test");
    })
    ->breakIfFailed()
    ->addAction(function () {
        dump("=====================================");
        dump(3);
    })
    ->addAction(new StateLassAction())
    ->addAction(
        new PipeLineTestAction()
            ->withArguments(
                arg1: "test",
                arg2: "test2"
            )
    )
    ->finished(fn() => dump("done"))
    ->onBreak(fn() => dump("break"))
    ->run();

```
