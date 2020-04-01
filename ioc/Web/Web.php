<?php

namespace Ioc\Web;

use Eywa\Cache\FileCache;
use Eywa\Html\Form\Form;
use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class Web extends Container
{

    /**
     * @inheritDoc
     */
    public function add(): Ioc
    {
        return
            $this->set('cache', new FileCache());
    }
}
