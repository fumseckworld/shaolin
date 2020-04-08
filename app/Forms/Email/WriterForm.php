<?php

namespace App\Forms\Email {

    use Eywa\Html\Form\Form;
    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Response\Response;

    class WriterForm extends Form
    {

        /**
         * @inheritDoc
         */
        protected static string $method = 'POST';

        /**
         * @inheritDoc
         */
        protected static string $route = 'send';

        /**
         * @inheritDoc
         */
        protected static array $route_args = [];

        /**
         * @inheritDoc
         */
        protected static array $options = [];

        /**
         * @inheritDoc
         */
        public static string $redirect_success_url = '/';

        /**
         * @inheritDoc
         */
        public static string $redirect_error_url = '/error';


        /**
         * @inheritDoc
         */
        public function make(): string
        {
            return '';
        }


        /**
         * @inheritDoc
         */
        public function success(Bag $bag): Response
        {
            // TODO: Implement success() method.
        }
    }
}
