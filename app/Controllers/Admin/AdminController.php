<?php

namespace App\Controllers\Admin {

    use Eywa\Http\Controller\Crud;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;

    class AdminController extends Crud
    {
        protected static string $layout = 'admin';
    
        protected static string $directory = 'Admin';

        /**
         * @inheritDoc
         */
        public function before_action(Request $request): void
        {
            // TODO: Implement before_action() method.
        }

        /**
         * @inheritDoc
         */
        public function after_action(Request $request): void
        {
            // TODO: Implement after_action() method.
        }

        /**
         * @inheritDoc
         */
        public function destroy(Request $request): Response
        {
            // TODO: Implement destroy() method.
        }

        /**
         * @inheritDoc
         */
        public function update(Request $request): Response
        {
            // TODO: Implement update() method.
        }

        /**
         * @inheritDoc
         */
        public function create(Request $request): Response
        {
            // TODO: Implement create() method.
        }

        /**
         * @inheritDoc
         */
        public function show(Request $request): Response
        {
            // TODO: Implement show() method.
        }
    }
}
