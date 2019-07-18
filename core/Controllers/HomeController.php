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
            $form = $this->form()->start('add-repository')

                    ->row()
                        ->input(Form::TEXT,'repository','The project name','<i class="material-icons">apps</i>   ','','','',true,true)
                    ->end_row_and_new()
                ->input(Form::EMAIL,'email','Bugs report email','<i class="material-icons">email</i>')
                ->end_row_and_new()
                        ->textarea('description','The repository description')
                ->end_row_and_new()
                        ->submit('create','<i class="material-icons">add</i>')
                ->end_row()->get();
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
