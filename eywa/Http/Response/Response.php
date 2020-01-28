<?php


namespace Eywa\Http\Response {


    use Eywa\Exception\Kedavra;

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
         */
        private array $headers = [];

        private array $status_text = STATUS;


        /**
         * Response constructor.
         *
         * @param string $content
         * @param int $status
         * @param array $headers
         *
         * @throws Kedavra
         */
        public function __construct(string $content, int $status = 200, array $headers = [])
        {
            $this->set_content($content)->set_status($status)->set_headers($headers);
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
        public function set_status(int $status): Response
        {
            not_in(STATUS_CODES,$status,true,'The status code is not valid');

            $this->status = $status;

            return $this;
        }

        /**
         *
         * Set the headers
         *
         * @param array $headers
         *
         * @return Response
         *
         */
        public function set_headers(array $headers): Response
        {
            $this->headers = $headers;
            return $this;
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
        public function set_content(string $content): Response
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
            return $this->send_headers()->send_content();
        }

        /**
         * Sends content for the current web response.
         *
         * @return $this
         */
        public function send_content()
        {
            if (php_sapi_name() !== 'cli')
                echo $this->content();

            return $this;
        }

        /**
         *
         * Send headers
         *
         * @return Response
         *
         */
        public function send_headers(): Response
        {
            // headers have already been sent by the developer
            if (headers_sent())
                return $this;

            foreach ($this->headers as $k => $v)
            {
                $replace = strcasecmp($k, 'Content-Type') === 0;
                header("$k:$v",$replace,$this->status());
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
            return  $this->content;
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
         * @param int $key
         *
         * @return bool
         *
         */
        public function is(int $key): bool
        {
            return $this->status === $key;
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


    }
}