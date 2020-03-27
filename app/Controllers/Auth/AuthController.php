<?php

namespace App\Controllers\Auth {

    use App\Forms\Auth\LoginForm;
    use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;

    class AuthController extends Controller
    {
        protected static string $layout = 'layout';
    
        protected static string $directory = 'Auth';
        
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

        public function logout(Request $request)
        {
            return $this->auth()->logout();
        }
        public function show_home(Request $request)
        {
            return $this->view('home', 'Welcome', 'wecome');
        }
        public function show_login_form(Request $request)
        {
            $form = $this->form(LoginForm::class);
            return $this->view('login', 'Login', 'Connexion for members', compact('form'));
        }
        public function login(Request $request)
        {
            return $this->check(LoginForm::class, $request);
        }
    }
}
