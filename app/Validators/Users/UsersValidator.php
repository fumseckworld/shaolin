<?php

namespace App\Validators\Users {

    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use Eywa\Validate\Validator;

    class UsersValidator extends Validator
    {

        public static string $redirect_url = '/error';

        public static array $rules =
        [

            'email' => 'email|required',
            'name' => 'required|unique:auth|max:5|min:3',
            'age'     => 'numeric|between:0,100'
        ];

        /**
         * @inheritDoc
         */
        protected static function do(Request $request): Response
        {
            return  new Response('valid');
        }
    }
}
            