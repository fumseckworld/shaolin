<?php


namespace Eywa\Ioc {

    /**
     *
     * Class Container
     *
     * @package Eywa\Ioc
     *
     */
    abstract class Container extends Ioc
    {
        abstract public function add(): Ioc;
    }
}
