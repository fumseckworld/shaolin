<?php

namespace ________________________________________________________\Controllers { 

	use Imperium\Controller\Controller;
    use Imperium\Exception\Kedavra;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    Class HomeController extends Controller
	{

		public function before_action()
		{

		}


        /**
         * @return string
         * @throws Kedavra
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
		public function app()
        {
            return $this->view('app');
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
