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

namespace Imperium\Http\Response {

    use Imperium\Exception\Kedavra;

    /**
     *
     * Represent an redirect response to redirect user on the website.
     *
     * This package contains all useful methods to interact with this redirection.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Response\RedirectResponse
     * @version 12
     *
     * @property    Response    $response The response instance.
     *
     *
     */
    class RedirectResponse
    {

        /**
         *
         * RedirectResponse constructor.
         *
         * @param string $url
         * @param int $status
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $url, int $status = 301)
        {
            $this->response = new Response('', $url, $status, ['Location' => $url]);
        }

        /**
         *
         * Send the response
         *
         * @return Response
         *
         */
        public function send(): Response
        {
            return $this->response->send();
        }
    }
}
