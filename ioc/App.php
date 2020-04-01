<?php

namespace Ioc;

use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class App extends Container
{
    public function add(): Ioc
    {
        return $this;
    }
}
