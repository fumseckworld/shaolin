<?php

namespace App\Controllers\Email { 

	use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;
    
	Class WriteController extends Controller
	{

        protected static string $layout = 'layout';
    
        protected static string $directory = 'Email';
        
        
        /**
         * @inheritDoc
         */
        public function before_validation(Request $request)
        {
            // TODO: Implement before_validation() method.
        }

        /**
         * @inheritDoc
         */
        public function after_validation(Request $request)
        {
            // TODO: Implement after_validation() method.
        }

        /**
         * @inheritDoc
         */
        public function before_save(Request $request)
        {
            // TODO: Implement before_save() method.
        }

        /**
         * @inheritDoc
         */
        public function after_save(Request $request)
        {
            // TODO: Implement after_save() method.
        }

        /**
         * @inheritDoc
         */
        public function after_commit(Request $request)
        {
            // TODO: Implement after_commit() method.
        }

        /**
         * @inheritDoc
         */
        public function after_rollback(Request $request)
        {
            // TODO: Implement after_rollback() method.
        }

        /**
         * @inheritDoc
         */
        public function before_update(Request $request)
        {
            // TODO: Implement before_update() method.
        }

        /**
         * @inheritDoc
         */
        public function after_update(Request $request)
        {
            // TODO: Implement after_update() method.
        }

        /**
         * @inheritDoc
         */
        public function before_action(Request $request)
        {
            // TODO: Implement before_action() method.
        }

        /**
         * @inheritDoc
         */
        public function after_action(Request $request)
        {
            // TODO: Implement after_action() method.
        }

        /**
         * @inheritDoc
         */
        public function before_create(Request $request)
        {
            // TODO: Implement before_create() method.
        }

        /**
         * @inheritDoc
         */
        public function after_create(Request $request)
        {
            // TODO: Implement after_create() method.
        }

        /**
         * @inheritDoc
         */
        public function before_destroy(Request $request)
        {
            // TODO: Implement before_destroy() method.
        }

        /**
         * @inheritDoc
         */
        public function after_destroy(Request $request)
        {
            // TODO: Implement after_destroy() method.
        }
        
    }
}