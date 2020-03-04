<?php


namespace Ioc;


use Eywa\Http\Request\Request;
use Eywa\Http\View\View;
use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class Admin extends Container
{

    public function build(): Ioc
    {
       return
           $this->init(Request::class,function (){
            return Request::make();
       })->set('aladin','rice');
    }
}