<?php


namespace Eywa\Http\Controller {

    use Eywa\Application\App;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;

    abstract class Crud extends App
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

        /**
         *
         * Remove a record
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        abstract public function destroy(Request $request): Response;

        /**
         *
         * Update a record
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        abstract public function update(Request $request): Response;

        /**
         *
         * Create a new record
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         *
         *
         */
        abstract public function create(Request $request): Response;

        /**
         *
         * Show records
         *
         * @param Request $request
         *
         * @return Response
         *
         */
        abstract public function show(Request $request): Response;
    }
}
