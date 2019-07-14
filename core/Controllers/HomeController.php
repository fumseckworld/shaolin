<?php

namespace App\Controllers {

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
