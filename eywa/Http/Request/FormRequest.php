<?php

declare(strict_types=1);

namespace Eywa\Http\Request {


    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;


    class FormRequest
    {
        /**
         *
         * The form action
         *
         */
        private string $url;

        /**
         *
         * The form method
         *
         */
        private string $method;

        /**
         *
         * The form tag options
         *
         * @var array<string>
         *
         */
        private array $options;


        /**
         *
         * All form errors
         *
         * @var array<string>
         *
         */
        private array $errors;


        /**
         *
         * FormRequest constructor.
         *
         * @param string $url
         * @param string $method
         * @param array<string> $errors
         * @param array<string> $options
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $url,string $method,array $errors = [],array $options =[])
        {
            $this->url = $url;
            $this->method = strtoupper($method);
            $this->options = $options;
            $this->errors = $errors;
            not_in(METHOD_SUPPORTED,$this->method,true,sprintf('The %s method is not supported',$this->method));
        }

        /**
         *
         * Get the url
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
         * Get the url
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
         * Get the form tag options
         *
         * @return array<string>
         *
         */
        public function options(): array
        {
            return $this->options;
        }

        /**
         *
         * Get the form errors
         *
         * @return Collect
         *
         */
        public function errors(): Collect
        {
            return collect($this->options);
        }
    }
}