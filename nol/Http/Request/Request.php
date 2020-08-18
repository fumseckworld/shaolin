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


    use Nol\Exception\Kedavra;
    use Nol\Http\Parameters\Bag;
    use Nol\Http\Parameters\UploadedFile;


    /**
     *
     * Represent an user request on the website.
     *
     * This package contains all useful methods to interact with all values.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Request
     * @version 12
     *
     * @property    Bag          $query      The container for $_GET values.
     * @property    Bag          $request    The container for $_POST values.
     * @property    Bag          $cookie     The container for $_COOKIE values
     * @property    Bag          $server     The container for $_SERVER values.
     * @property    Bag          $args       The container for the router params values.
     * @property    UploadedFile $files      The container for the uploaded files.
     *
     */
    final class Request
    {
        /**
         *
         * Initialize a new request
         *
         * @param array $request     The $_POST values.
         * @param array $query       The $_GET values.
         * @param array $cookies     The $_COOKIE values.
         * @param array $files       The $_FILES values.
         * @param array $server      The $_SERVER values.
         * @param array $router_args The router args for a route.
         */
        public function __construct(
            array $request = [],
            array $query = [],
            array $cookies = [],
            array $files = [],
            array $server = [],
            array $router_args = []
        ) {
            $this->initialize($query, $request, $cookies, $files, $server, $router_args);
        }

        /**
         *
         * Save all missing values to use with the container.
         *
         * @param array $request     The $_POST values.
         * @param array $query       The $_GET values.
         * @param array $cookies     The $_COOKIE values.
         * @param array $files       The $_FILES values.
         * @param array $server      The $_SERVER values.
         * @param array $router_args The router args for a route.
         *
         * @return Request
         *
         */
        final public function set(
            array $request = [],
            array $query = [],
            array $cookies = [],
            array $files = [],
            array $server = [],
            array $router_args = []
        ): self {
            return new self($request, $query, $cookies, $files, $server, $router_args);
        }

        /**
         *
         * Set a bag content.
         *
         * @param string $key   The bag key.
         * @param array  $value THe bag value.
         *
         * @throws Kedavra
         *
         * @return Request
         *
         */
        final public function with(string $key, array $value): Request
        {
            if (
                !in_array($key, ['post', 'get', 'cookie', 'file', 'server', 'args'])
            ) {
                throw new Kedavra(
                    sprintf(
                        'Only the %s keys are valid',
                        join(', ', ['post', 'get', 'cookie', 'file', 'server', 'args'])
                    )
                );
            }
            switch ($key) {
                case 'post':
                    $this->request = new  Bag($value);
                    break;
                case 'get':
                    $this->query = new Bag($value);
                    break;
                case 'cookie':
                    $this->cookie = new Bag($value);
                    break;
                case 'file':
                    $this->files = new UploadedFile($value);
                    break;
                case 'server':
                    $this->server = new Bag($value);
                    break;
                default:
                    $this->args = new Bag($value);
                    break;
            }
            return $this;
        }

        /**
         *
         * Check if the request is secure.
         *
         * Return true if the request is secure or false on insecure.
         *
         * @return boolean
         *
         */
        final public function secure(): bool
        {
            return cli() ? false : intval($this->server->get('SERVER_PORT')) === 443;
        }

        /**
         *
         * Creates a new request with values from PHP's super globals.
         *
         * @param array<mixed> $args
         *
         * @return Request
         *
         *
         */
        final public static function make(array $args = []): Request
        {
            return new self($_POST, $_GET, $_COOKIE, $_FILES, $_SERVER, $args);
        }

        /**
         *
         * Return an instance of $_GET container.
         *
         * @return Bag
         *
         */
        final public function query(): Bag
        {
            return $this->query;
        }


        /**
         *
         * Return an instance of route args container.
         *
         * @return Bag
         *
         */
        final public function args(): Bag
        {
            return $this->args;
        }

        /**
         *
         * Return an instance of the $_POST container.
         *
         * @return Bag
         *
         */
        final public function request(): Bag
        {
            return $this->request;
        }

        /**
         *
         * Return an instance of the $_SERVER container.
         *
         * @return Bag
         *
         */
        final public function server(): Bag
        {
            return $this->server;
        }

        /**
         *
         * Return an instance of $_FILES container
         *
         * @return UploadedFile
         *
         */
        final public function files(): UploadedFile
        {
            return $this->files;
        }

        /**
         *
         * Return an instance of the $_COOKIE container
         *
         * @return Bag
         *
         */
        final public function cookie(): Bag
        {
            return $this->cookie;
        }

        /**
         *
         * Initialize the correct object of a request type.
         *
         * @param array $query
         * @param array $request
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param array $router_args
         *
         */
        final private function initialize(
            array $query,
            array $request,
            array $cookies,
            array $files,
            array $server,
            array $router_args
        ): void {
            $this->query = new Bag($query);
            $this->request = new Bag($request);
            $this->cookie = new Bag($cookies);
            $this->files = new UploadedFile($files);
            $this->server = new Bag($server);
            $this->args = new Bag($router_args);
        }

        /**
         *
         * Get the connected user ip
         *
         * @return string
         *
         */
        final public function ip(): string
        {
            return cli() ? '127.0.0.1' : strval($this->server()->get('REMOTE_ADDR'));
        }

        /**
         *
         * Check if the request is in local.
         *
         * Return true if is in local or false on production.
         *
         * @return boolean
         *
         */
        final public function local(): bool
        {
            return strcmp($this->ip(), '127.0.0.1') === 0;
        }

        /**
         *
         * Check if the token has been defined and are valid.
         *
         * @return bool
         *
         */
        final public function hasToken(): bool
        {
            return $this->request()->has('form_token');
        }

        /**
         *
         * Check if the request is submitted.
         *
         * @return bool
         *
         */
        final public function submitted(): bool
        {
            return $this->request()->count() > 0;
        }
    }
}
