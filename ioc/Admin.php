<?php


namespace Ioc;

use Eywa\Http\Request\Request;
use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class Admin extends Container
{

    /**
     * @inheritDoc
     */
    public function add(): Ioc
    {
        return
           $this->init(Request::class, function () {
               return Request::make();
           })->set('aladin', 'rice');
    }
}
