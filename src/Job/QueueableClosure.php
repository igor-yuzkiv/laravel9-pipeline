<?php

namespace Pipeline\Job;

use Laravel\SerializableClosure\SerializableClosure;

class QueueableClosure extends SerializableClosure
{
    /**
     * @return mixed
     */
    public function handle(): mixed
    {
        return call_user_func_array($this->serializable, func_get_args());
    }

}
