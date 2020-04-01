<?php

namespace App\Controllers\Demo {

    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;

    class Demo extends Controller
    {
        protected static string $layout = 'layout';
    
        protected static string $directory = 'Demo';

        /**
         * @inheritDoc
         */
        public function before(Request $request): void
        {
            // TODO: Implement before() method.
        }

        /**
         * @inheritDoc
         */
        public function after(Request $request): void
        {
            // TODO: Implement after() method.
        }
    }
}
