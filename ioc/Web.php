<?php


namespace Ioc;


use Eywa\Html\Form\Form;
use Eywa\Http\Request\FormRequest;
use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class Web extends Container
{

    /**
     * @inheritDoc
     */
    public function build(): Ioc
    {
        return
            $this->init(Form::class,function (){

            });
    }
}