<?php

namespace App\Controllers\Home {

    use App\Models\Users\Users;
    use App\Validators\Home\HomeValidator;
    use App\Validators\Users\UsersValidator;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use ReflectionException;

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


        public function notFound()
        {
            return $this->view('404', 'not found', 'error');
        }

        /**
         *
         * @param Request $request
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        public function home(Request $request): Response
        {
            $users = $this->model(Users::class, 'all');
            return $this->view('home', 'A library to make mvc website', 'The core of shaolin', compact('users'));
        }

        public function hello(Request $request): Response
        {
            return $this->view('hello', 'say hello', 'hello', ['name' => $request->args()->get('name', 'jean')]);
        }
    }
}
