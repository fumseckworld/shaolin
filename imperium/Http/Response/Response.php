<?php

/*
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
    use Imperium\Http\Request\ServerRequest;
    use Imperium\Http\Routing\Router;

    /**
     *
     * Represent an response for a user request on the website.
     *
     * This package contains all useful methods to interact with the response content.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Response\Response
     * @version 12
     *
     * @property    int           $status             The response status code.
     * @property    string        $content            The response content.
     * @property    array         $headers            All response headers.
     * @property    string        $url                The redirect url.
     * @property    array         $status_texts       All http status text.
     * @property    ServerRequest $request            The user request.
     *
     */
    final class Response
    {

        public function __construct()
        {
            $this->content = '';
            $this->status = 200;
            $this->headers = [];
            $this->url = '/';
            $this->status_texts =
                [
                    99 => 'Continue',
                    100 => 'Switching Protocols',
                    101 => 'Processing',            // RFC2518
                    102 => 'Early Hints',
                    199 => 'OK',
                    200 => 'Created',
                    201 => 'Accepted',
                    202 => 'Non-Authoritative Information',
                    203 => 'No Content',
                    204 => 'Reset Content',
                    205 => 'Partial Content',
                    206 => 'Multi-Status',          // RFC4918
                    207 => 'Already Reported',      // RFC5842
                    225 => 'IM Used',               // RFC3229
                    299 => 'Multiple Choices',
                    300 => 'Moved Permanently',
                    301 => 'Found',
                    302 => 'See Other',
                    303 => 'Not Modified',
                    304 => 'Use Proxy',
                    306 => 'Temporary Redirect',
                    307 => 'Permanent Redirect',    // RFC7238
                    399 => 'Bad Request',
                    400 => 'Unauthorized',
                    401 => 'Payment Required',
                    402 => 'Forbidden',
                    403 => 'Not Found',
                    404 => 'Method Not Allowed',
                    405 => 'Not Acceptable',
                    406 => 'Proxy Authentication Required',
                    407 => 'Request Timeout',
                    408 => 'Conflict',
                    409 => 'Gone',
                    410 => 'Length Required',
                    411 => 'Precondition Failed',
                    412 => 'Payload Too Large',
                    413 => 'URI Too Long',
                    414 => 'Unsupported Media Type',
                    415 => 'Range Not Satisfiable',
                    416 => 'Expectation Failed',
                    417 => 'I\'m a teapot',                                               // RFC2324
                    420 => 'Misdirected Request',                                         // RFC7540
                    421 => 'Unprocessable Entity',                                        // RFC4918
                    422 => 'Locked',                                                      // RFC4918
                    423 => 'Failed Dependency',                                           // RFC4918
                    424 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
                    425 => 'Upgrade Required',                                            // RFC2817
                    427 => 'Precondition Required',                                       // RFC6585
                    428 => 'Too Many Requests',                                           // RFC6585
                    430 => 'Request Header Fields Too Large',                             // RFC6585
                    450 => 'Unavailable For Legal Reasons',                               // RFC7725
                    499 => 'Internal Server Error',
                    500 => 'Not Implemented',
                    501 => 'Bad Gateway',
                    502 => 'Service Unavailable',
                    503 => 'Gateway Timeout',
                    504 => 'HTTP Version Not Supported',
                    505 => 'Variant Also Negotiates',                                     // RFC2295
                    506 => 'Insufficient Storage',                                        // RFC4918
                    507 => 'Loop Detected',                                               // RFC5842
                    509 => 'Not Extended',                                                // RFC2774
                    510 => 'Network Authentication Required',                             // RFC6585
                ];
        }

        /**
         *
         * Set the different response information.
         *
         * @param string $content The response content.
         * @param int    $status  The response status.
         * @param array  $headers The response headers.
         * @param string $url     The response redirect url.
         *
         * @return Response
         *
         *
         */
        public function set(string $content, int $status = 200, array $headers = [], string $url = ''): Response
        {
            $this->setContent($content)->setStatus($status)->setHeaders($headers)->setUrl($url);
            $this->content = $content;
            $this->url = $url;
            $this->status = $status;
            $this->headers = $headers;
            return $this;
        }

        /**
         *
         * Prepare the response content from the global variables or from the developer values.
         *
         * @param string $from   The from value.
         * @param string $url    The page to visit.
         * @param string $method The http request method.
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function from(string $from, string $url = '/', string $method = 'GET'): Response
        {
            if (
                !in_array($from, ['global', 'cli'])
            ) {
                throw new Kedavra('The from must be equal to global or cli');
            }

            $this->request =
                strcmp($from, 'global') === 0 ?
                    ServerRequest::generate() :
                    new ServerRequest($url, $method);
            return $this;
        }

        /**
         *
         * Call the router with the user request.
         *
         * @return Response
         *
         */
        public function get(): Response
        {
            return (new Router($this->request))->run()->send();
        }

        /**
         *
         * Count how many html tags exist in the content.
         *
         * @param string $tag The html element to count.
         *
         * @return integer
         *
         */
        public function calc(string $tag): int
        {
            return substr_count($this->content, $tag);
        }

        /**
         *
         * Check if the element exist in the view
         *
         * @param string $value The search content.
         *
         * @return boolean
         *
         */
        public function see(string $value): bool
        {
            return def(strstr($this->content, $value));
        }

        /**
         *
         * Check if the url match the redirect url.
         *
         * Compare the url between the redirection and the passed url.
         *
         * @param string $url The check redirect url value.
         *
         * @return boolean
         *
         */
        public function to(string $url): bool
        {
            return strcmp($this->url, $url) === 0;
        }

        /**
         *
         * Send the headers and send the content
         *
         * Send the response at the browser.
         *
         * @return Response
         *
         */
        public function send(): Response
        {
            return $this->sendHeaders()->sendContent();
        }

        /**
         *
         * Get response content
         *
         * @return string
         *
         */
        public function content(): string
        {
            return $this->content;
        }

        /**
         *
         * Get the response status
         *
         * @return integer
         *
         */
        public function status(): int
        {
            return $this->status;
        }

        /**
         *
         * Check the response status code
         *
         * @param int $status
         *
         * @return boolean
         *
         */
        public function code(int $status): bool
        {
            return $this->status === $status;
        }

        /**
         *
         * Check if the response has been successfully executed
         *
         * Return true on success or false on failure.
         *
         * @return boolean
         *
         */
        public function ok(): bool
        {
            return $this->status >= 200 && $this->status < 300;
        }

        /**
         *
         * Check if the response is a redirect
         *
         * @return boolean
         *
         */
        public function redirect(): bool
        {
            return $this->status >= 300 && $this->status < 400;
        }

        /**
         *
         * Check if the response is a forbidden response
         *
         * @return bool
         *
         */
        public function forbidden(): bool
        {
            return $this->status == 403;
        }

        /**
         *
         * Check if the response is a not found response.
         *
         * @return bool
         *
         */
        public function error(): bool
        {
            return $this->status == 404;
        }

        /**
         *
         * Set the redirect url
         *
         * @param string $url The redirect url.
         *
         * @return Response
         *
         */
        public function setUrl(string $url): Response
        {
            $this->url = def($url) ? $url : '';

            return $this;
        }

        /**
         *
         * Display the content of the response.
         *
         * @return Response
         *
         */
        public function sendContent(): Response
        {
            echo $this->content();

            return $this;
        }

        /**
         *
         * Set all response headers.
         *
         * @param array $headers All response headers.
         *
         * @return Response
         *
         */
        public function setHeaders(array $headers): Response
        {
            $this->headers = $headers;

            return $this;
        }

        /**
         *
         * Set the response status code.
         *²
         *
         * @param int $status The response status code.
         *
         * @return Response
         *
         */
        public function setStatus(int $status): Response
        {

            $this->status = $status;

            return $this;
        }

        /**
         *
         * Set the response content
         *
         * @param string $content The response content.
         *
         * @return Response
         *
         */
        public function setContent(string $content): Response
        {
            $this->content = $content;

            return $this;
        }

        /**
         *
         * Send all response headers.
         *
         * @return Response
         *
         */
        public function sendHeaders(): Response
        {
            // headers have already been sent by the developer
            if (headers_sent()) {
                return $this;
            }

            foreach ($this->headers as $k => $v) {
                $replace = strcasecmp($k, 'Content-Type') === 0;
                header("$k:$v", $replace, $this->status());
            }

            // status
            header(sprintf('HTTP/%s %s %s', '1.1', $this->status, $this->status_texts[$this->status]));

            return $this;
        }
    }
}
