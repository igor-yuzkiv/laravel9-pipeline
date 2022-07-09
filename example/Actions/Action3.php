<?php

namespace Example\Actions;

use Pipeline\Contracts\Runnable;

class Action3 implements Runnable
{
    public function run()
    {
        dump(static::class);
    }
}

