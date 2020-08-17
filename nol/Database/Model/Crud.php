<?php

namespace Nol\Database\Model {

    use Nol\Http\Request\Request;
    use Nol\Http\Response\Response;

    /**
     *
     * Base class to create a crud.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Nol\Database\Model\Crud
     * @version 12
     *
     */
    abstract class Crud extends Model
    {
        /**
         *
         * Create a new record.
         *
         * @param Request $request The user request.
         *
         * @return Response
         *
         */
        abstract public function create(Request $request): Response;


        /**
         *
         * Get records with a pagination.
         *
         * @param Request $request The user request.
         *
         * @return Response
         *
         */
        abstract public function read(Request $request): Response;

        /**
         *
         * Update a record.
         *
         * @param Request $request The user request.
         *
         * @return Response
         *
         */
        abstract public function update(Request $request): Response;

        /**
         *
         * Remove a record or multiples records.
         *
         * @param Request $request The user request.
         *
         * @return Response
         *
         */
        abstract public function delete(Request $request): Response;
    }
}
