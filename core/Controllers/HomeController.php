<?php

namespace Shaolin\Controllers {

	use Imperium\Controller\Controller;
    use Imperium\Exception\Kedavra;
    use Imperium\Html\Form\Form;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    Class HomeController extends Controller
	{

		public function before_action()
		{

		}

        public function home()
        {

           $repositories =  display_repositories(logged_user());
           $form =  create_repository();

           return $this->view('home',compact('form','repositories'));
        }

        public function admin()
        {
            return $this->view('admin');
        }

        public function alex()
        {
            return $this->view('alex');
        }
		public function logout()
        {
            return $this->auth()->logout();
        }
		public function not_found()
        {
            return $this->view('404');
        }
	}

}
