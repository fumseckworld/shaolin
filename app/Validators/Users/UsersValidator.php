<?php

namespace App\Validators\Users {

    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Response\Response;
    use Eywa\Validate\Validator;

    class UsersValidator extends Validator
    {
        public static string $redirect_url = '/error';

        public static array $rules =
        [
            'email' => 'email|required',
            'username' => 'required|unique:auth|max:25|min:3',
            'age'     => 'numeric|between:0,100'
        ];

        /**
         * @inheritDoc
         */
        public function success(Bag $request): Response
        {
            // TODO: Implement success() method.
        }

        /**
         * @inheritDoc
         */
        public function error(array $messages): Response
        {
            // TODO: Implement error() method.
        }
    }
}
