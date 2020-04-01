<?php

namespace App\Controllers\Auth {

    use App\Forms\Auth\LoginForm;
    use App\Models\Auth\Authentication;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;

    class AuthController extends Controller
    {
        protected static string $layout = 'layout';
    
        protected static string $directory = 'Auth';
        
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

        /**
         * @param Request $request
         * @return Response
         * @throws Kedavra
         */
        public function logout(Request $request)
        {
            return $this->auth(Authentication::class)->logout();
        }

        /**
         * @param Request $request
         * @return Response
         * @throws Kedavra
         */
        public function showHome(Request $request)
        {
            return $this->view('home', 'Welcome', 'wecome');
        }

        /**
         * @param Request $request
         * @return Response
         * @throws Kedavra
         */
        public function showLoginForm(Request $request)
        {
            $form = $this->form(LoginForm::class);
            return $this->view('login', 'Login', 'Connexion for members', compact('form'));
        }

        /**
         * @param Request $request
         * @return Response
         */
        public function login(Request $request)
        {
            return $this->check(LoginForm::class, $request);
        }
    }
}
