<?php

declare(strict_types=1);

namespace Eywa\Http\Controller {

    use Eywa\Application\App;
    use Eywa\Http\Request\Request;

    /**
     *
     * Class Controller
     *
     * @package Eywa\Http\Controller
     *
     */
    abstract class Controller extends App
    {
        /**
         *
         * Function executed before an action
         *
         * @param Request $request
         *
         */
        abstract public function before_action(Request $request): void;

        /**
         *
         * Function executed after an action
         *
         * @param Request $request
         *
         */
        abstract public function after_action(Request $request):void;
    }
}
