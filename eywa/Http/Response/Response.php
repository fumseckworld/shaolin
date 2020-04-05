<?php

declare(strict_types=1);

namespace Eywa\Http\Response {


    use Eywa\Exception\Kedavra;
    use Eywa\Message\Flash\Flash;

    class Response
    {
        /**
         *
         * The response content
         *
         */
        private string $content = '';

        /**
         *
         * Response status code
         *
         */
        private int $status = 200;

        /**
         *
         * All headers
         *
         * @var array<string>
         *
         */
        private array $headers = [];

        /**
         *
         * The status text and codes
         *
         * @var array<int,string>
         *
         */
        private array $status_text = STATUS;

        /**
         *
         * The redirect url
         *
         */
        private string $url = '';


        /**
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
        public function __construct(string $content, string $url = '', int $status = 200, array $headers = [])
        {
            $this->setContent($content)->setStatus($status)->setHeaders($headers)->setUrl($url);
        }

        /**
         *
         * Count how many tag exist in content
         *
         * @param string $tag
         *
         * @return int
         *
         */
        public function count(string $tag): int
        {
            return substr_count($this->content, $tag);
        }

        /**
         *
         * Check if the element exist in the view
         *
         * @param string $element
         *
         * @return bool
         *
         */
        public function exist(string $element): bool
        {
            return def(strstr($this->content, $element));
        }

        /**
         *
         *
         * @param string $message
         * @param bool $success
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function flash(string $message, bool $success = true): Response
        {
            $success ? (new Flash())->set(SUCCESS, $message) : (new Flash())->set(FAILURE, $message);

            return $this;
        }

        /**
         *
         *
         * Set the status code
         *
         * @param int $status
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function setStatus(int $status): Response
        {
            not_in(STATUS_CODES, $status, true, 'The status code is not valid');

            $this->status = $status;

            return $this;
        }


        /**
         *
         * Set the headers
         *
         * @param array<string> $headers
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
         * Check if the redirect url equal the url
         *
         * @param string $url
         *
         * @return bool
         *
         */
        public function to(string $url): bool
        {
            return $this->url === $url;
        }

        /**
         *
         * Set the response content
         *
         * @param string $content
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
         * Send content
         *
         * @return Response
         *
         */
        public function send(): Response
        {
            return $this->sendHeaders()->sendContent();
        }

        /**
         * Sends content for the current web response.
         *
         * @return $this
         */
        public function sendContent()
        {
            if (not_cli()) {
                echo $this->content();
            }

            return $this;
        }

        /**
         *
         * Send headers
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
            header(sprintf('HTTP/%s %s %s', '1.1', $this->status, $this->status_text[$this->status]));

            return $this;
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
         * @return int
         *
         */
        public function status(): int
        {
            return $this->status;
        }

        /**
         *
         * Check the status
         *
         * @param int $status
         *
         * @return bool
         *
         */
        public function is(int $status): bool
        {
            return $this->status === $status;
        }


        /**
         * Is response successful?
         *
         *
         */
        public function success(): bool
        {
            return $this->status >= 200 && $this->status < 300;
        }

        /**
         * Is the response a redirect?
         *
         * @final
         */
        public function redirect(): bool
        {
            return $this->status >= 300 && $this->status < 400;
        }

        /**
         *
         * Set the redirect url
         *
         * @param string $url
         *
         * @return Response
         *
         */
        private function setUrl(string $url): Response
        {
            $this->url = def($url) ? $url : '';
            return $this;
        }
    }
}
