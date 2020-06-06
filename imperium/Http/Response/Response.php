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
     * Represent an response for a user request on the website.
     *
     * This package contains all useful methods to interact with the response content.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Response\Response
     * @version 12
     *
     * @property    int             $status         The response status code.
     * @property    string          $content        The response content.
     * @property    array           $headers        All response headers.
     * @property    string          $url     aa       The redirect url.
     * @property    array           $status_texts   All http status text.
     *
     *
     */
    final class Response
    {

        /**
         *
         * Response constructor.
         *
         * @param string $content
         * @param string $url
         * @param int $status
         * @param array<mixed> $headers
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $content = '', string $url = '', int $status = 200, array $headers = [])
        {
            $this->status_texts =
                [
                    100 => 'Continue',
                    101 => 'Switching Protocols',
                    102 => 'Processing',            // RFC2518
                    103 => 'Early Hints',
                    200 => 'OK',
                    201 => 'Created',
                    202 => 'Accepted',
                    203 => 'Non-Authoritative Information',
                    204 => 'No Content',
                    205 => 'Reset Content',
                    206 => 'Partial Content',
                    207 => 'Multi-Status',          // RFC4918
                    208 => 'Already Reported',      // RFC5842
                    226 => 'IM Used',               // RFC3229
                    300 => 'Multiple Choices',
                    301 => 'Moved Permanently',
                    302 => 'Found',
                    303 => 'See Other',
                    304 => 'Not Modified',
                    305 => 'Use Proxy',
                    307 => 'Temporary Redirect',
                    308 => 'Permanent Redirect',    // RFC7238
                    400 => 'Bad Request',
                    401 => 'Unauthorized',
                    402 => 'Payment Required',
                    403 => 'Forbidden',
                    404 => 'Not Found',
                    405 => 'Method Not Allowed',
                    406 => 'Not Acceptable',
                    407 => 'Proxy Authentication Required',
                    408 => 'Request Timeout',
                    409 => 'Conflict',
                    410 => 'Gone',
                    411 => 'Length Required',
                    412 => 'Precondition Failed',
                    413 => 'Payload Too Large',
                    414 => 'URI Too Long',
                    415 => 'Unsupported Media Type',
                    416 => 'Range Not Satisfiable',
                    417 => 'Expectation Failed',
                    418 => 'I\'m a teapot',                                               // RFC2324
                    421 => 'Misdirected Request',                                         // RFC7540
                    422 => 'Unprocessable Entity',                                        // RFC4918
                    423 => 'Locked',                                                      // RFC4918
                    424 => 'Failed Dependency',                                           // RFC4918
                    425 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
                    426 => 'Upgrade Required',                                            // RFC2817
                    428 => 'Precondition Required',                                       // RFC6585
                    429 => 'Too Many Requests',                                           // RFC6585
                    431 => 'Request Header Fields Too Large',                             // RFC6585
                    451 => 'Unavailable For Legal Reasons',                               // RFC7725
                    500 => 'Internal Server Error',
                    501 => 'Not Implemented',
                    502 => 'Bad Gateway',
                    503 => 'Service Unavailable',
                    504 => 'Gateway Timeout',
                    505 => 'HTTP Version Not Supported',
                    506 => 'Variant Also Negotiates',                                     // RFC2295
                    507 => 'Insufficient Storage',                                        // RFC4918
                    508 => 'Loop Detected',                                               // RFC5842
                    510 => 'Not Extended',                                                // RFC2774
                    511 => 'Network Authentication Required',                             // RFC6585
                ];

            $this->setContent($content)->setStatus($status)->setHeaders($headers)->setUrl($url);
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
        public function sum(string $tag): int
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
        public function is(int $status): bool
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
        public function success(): bool
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
