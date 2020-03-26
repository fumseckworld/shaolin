<?php

namespace App\Controllers\Home {

    use App\Forms\UsersForm;
    use App\Models\User;
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


        public function not_found()
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
         *
         */
        public function home(Request $request): Response
        {
            $users = User::all();

            $form = (new UsersForm())->make();


            return $this->view('home', 'A library to make mvc website', 'The core of shaolin', compact('users', 'form'));
        }

        public function hello(Request $request): Response
        {
            return $this->view('hello', 'say hello', 'hello', ['name'=> $request->args()->get('name', 'jean')]);
        }
    }
}
