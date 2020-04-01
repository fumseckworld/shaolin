<?php

namespace Ioc\Dev;

use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class Dev extends Container
{

    /**
     * @inheritDoc
     */
    public function add(): Ioc
    {
        return $this;
    }
}
