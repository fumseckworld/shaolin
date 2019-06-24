<?php

namespace ________________________________________________________\Controllers {

	use Imperium\Controller\Controller;

	Class HomeController extends Controller
	{

		public function before_action()
		{

		}

		public function after_action()
		{

		}

		public function not_found()
        {
            return $this->view('404');
        }
		public function home()
        {
            $x = $this->form()->start('/commit','POST')->submit('a')->get();
            return $this->view('home',compact('x'));
        }

        public function commit()
        {
            d($this->request());
            $this->back();
        }

        public function  edit(string $name,int $id)
        {
            return $this->view('edit',compact('name','id'));
        }

	}

}
