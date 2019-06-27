<?php

namespace ________________________________________________________\Controllers { 

	use Imperium\Controller\Controller;
    use Imperium\Directory\Dir;

    Class GitController extends Controller
	{

		public function before_action()
		{

		}

		public function after_action()
		{

		}

		public function repositories()
        {

            $data = $this->scan_repositories();




            return $this->view('repositories',compact('data'));

        }

	}

}
