<?php

namespace App\Controllers\Home {

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;

    class HomeController extends Controller
    {
        protected static string $layout = 'layout';
    
        protected static string $directory = 'Home';
        
        /**
         * @inheritDoc
         */
        public function before_action(Request $request): void
        {
        }
        
        /**
         * @inheritDoc
         */
        public function after_action(Request $request): void
        {
        }


        /**
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function home(Request $request): Response
        {
            return $this->view('home', 'A library to make mvc website', 'The core of shaolin');
        }
    }
}
