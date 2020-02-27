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
         * Function executed before the validator
         *
         * @param Request $request
         *
         */
        abstract public function before_validation(Request $request);

        /**
         *
         * Function executed after the validation
         *
         * @param Request $request
         *
         */
        abstract public function after_validation(Request $request);


        /**
         *
         * Function executed before save a new reoord
         *
         * @param Request $request
         *
         */
        abstract public function before_save(Request $request);

        /**
         *
         * Function executed after data has been saved
         *
         * @param Request $request
         *
         */
        abstract public function after_save(Request $request);

        /**
         *
         * @param Request $request
         *
         */
        abstract public function after_commit(Request $request);

        /**
         *
         *
         * @param Request $request
         *
         */
        abstract public function after_rollback(Request $request);


        /**
         *
         * Function executed executed before update a record
         *
         * @param Request $request
         *
         */
        abstract public function before_update(Request $request);

        /**
         *
         * Function executed after record has been updated
         *
         * @param Request $request
         *
         */
        abstract public function after_update(Request $request);


        /**
         *
         * Function executed before an action
         *
         * @param Request $request
         *
         */
        abstract public function before_action(Request $request);

        /**
         *
         * Function executed after an action
         *
         * @param Request $request
         *
         */
        abstract public function after_action(Request $request);


        /**
         *
         * Function executed before create a new record
         *
         * @param Request $request
         *
         */
        abstract public function before_create(Request $request);

        /**
         *
         * Function executed after create a new record
         *
         * @param Request $request
         *
         */
        abstract public function after_create(Request $request);

        /**
         *
         * @param Request $request
         *
         */
        abstract public function before_destroy(Request $request);

        /**
         *
         * @param Request $request
         *
         */
        abstract public function after_destroy(Request $request);
    }
}