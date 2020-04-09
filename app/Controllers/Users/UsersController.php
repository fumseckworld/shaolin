<?php

namespace App\Controllers\Users {

    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;

    class UsersController extends Controller
    {
        protected static string $layout = 'users';
        
        protected static string $directory = 'Users';

        /**
         * @inheritDoc
         */
        public function before(Request $request): void
        {
            // TODO: Implement before_action() method.
        }

        /**
         * @inheritDoc
         */
        public function after(Request $request): void
        {
            // TODO: Implement after_action() method.
        }
    }
}
