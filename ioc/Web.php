<?php


namespace Ioc;


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
            $this->init(Form::class,function (){
                return 'form';
            });
    }
}