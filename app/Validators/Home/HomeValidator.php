<?php

namespace App\Validators\Home {

    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Response\Response;
    use Eywa\Validate\Validator;

    class HomeValidator extends Validator
    {
        public static string $redirect_success_url = '/home';

        public static string $redirect_error_url = '/error';

        public static array $rules =
        [
            'REMOTE_ADDR' => '127.0.0.1'
        ];

        /**
         * @inheritDoc
         */
        public function success(Bag $bag): Response
        {
            return new Response('ok');
        }

        /**
         * @inheritDoc
         */
        public function error(array $messages): Response
        {
            return $this->redirect(static::$redirect_error_url, $messages, false);
        }
    }
}
