<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace Nol\Http\Request {

    use Nol\Http\Parameters\Bag;
    use Nol\Http\Parameters\UploadedFile;

    /**
     *
     * Represent an request used for the router.
     *
     * This package contains all useful methods to interact with the router request.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Request\ServerRequest
     * @version 12
     *
     * @property    string  $url            The request url.
     * @property    string  $method         The request method.
     * @property    Request $request        The request instance.
     *
     *
     */
    class ServerRequest
    {


        /**
         *
         * ServerRequest constructor.
         *
         * @param string $url    The url to visit.
         * @param string $method The method to access to code.
         *
         *
         */
        public function __construct(string $url, string $method = 'GET')
        {
            $this->url = $url;

            $this->method = strtoupper($method);

            $this->request = cli() ? new Request() : Request::make();
        }

        /**
         *
         * Check if the server url match the url
         *
         * @param string $url The url to verify.
         *
         * @return boolean
         *
         */
        public function match(string $url): bool
        {
            return strcmp($this->url(), $url) === 0;
        }

        /**
         *
         * Generate the request from global variable.
         *
         * @return ServerRequest
         *
         *
         */
        public static function generate(): ServerRequest
        {
            return cli()
                ?
                new self('/')
                : new self($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        }

        /**
         *
         * Return the server method used.
         *
         * @return string
         *
         */
        public function method(): string
        {
            return $this->method;
        }

        /**
         *
         * Get the server url used.
         *
         * @return string
         *
         */
        public function url(): string
        {
            return $this->url;
        }

        /**
         *
         * Get an instance of the $_POST container.
         *
         * @return Bag
         *
         */
        public function post(): Bag
        {
            return $this->request->request();
        }

        /**
         *
         * Check if the request is submitted by a form
         *
         * @return boolean
         *
         */
        public function submitted(): bool
        {
            return !in_array($this->method(), ['GET']);
        }

        /**
         *
         * Check if the request is executed on local.
         *
         * @return bool
         *
         */
        public function local(): bool
        {
            return $this->request->local();
        }


        /**
         *
         * Get an instance of the $_GET container.
         *
         * @return Bag
         *
         */
        public function get(): Bag
        {
            return $this->request->query();
        }

        /**
         *
         * Get an instance of the $_COOKIE container.
         *
         * @return Bag
         *
         */
        public function cookie(): Bag
        {
            return $this->request->cookie();
        }

        /**
         *
         * Get an instance of the $_SERVER container.
         *
         * @return Bag
         *
         */
        public function server(): Bag
        {
            return $this->request->server();
        }

        /**
         *
         * Get an instance of the $_FILES container.
         *
         * @return UploadedFile
         *
         */
        public function files(): UploadedFile
        {
            return $this->request->files();
        }


        /**
         *
         * Check if the request has the csrf token.
         *
         * @return boolean
         *
         */
        public function hasToken(): bool
        {
            return $this->request->request()->has('form_token');
        }
    }
}
