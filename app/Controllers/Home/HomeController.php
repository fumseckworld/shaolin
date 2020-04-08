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
        public function before(Request $request): void
        {
        }
        
        /**
         * @inheritDoc
         */
        public function after(Request $request): void
        {
        }


        /**
         * @return Response
         * @throws Kedavra
         */
        public function notFound()
        {
            return $this->view('404', 'not found', 'error');
        }

        /**
         *
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function showUsers(Request $request)
        {
            $users =  $this->model('users')->paginate(function ($user) {
                return
                    '<div>
                        <h3>' . $user->username . '</h3>
                        <p>
                            <a href="mailto:' . $user->email . '">' . $user->username . '</a>
                        </p>
                        <p>' . $user->phone . '</p>
                    </div>';
            }, 'users', $request->args()->get('page', '1'));

            return $this->view('users', 'show user', 'a suber paginaition', compact('users'));
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
            $users = $this->model('users')->all();

            return
                $this->view(
                    'home',
                    'A library to make mvc website',
                    'The core of shaolin',
                    compact('users')
                );
        }

        /**
         * @param Request $request
         * @return Response
         * @throws Kedavra
         */
        public function hello(Request $request): Response
        {
            $name = $request->args()->get('name', 'jean');
            return $this->view('hello', 'say hello', 'hello', compact('name'));
        }
    }
}
