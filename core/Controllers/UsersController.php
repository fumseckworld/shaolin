<?php

namespace Shaolin\Controllers {

    
    use Imperium\Controller\Controller;



    Class UsersController extends Controller
	{


        /**
         */
        public function before_action()
		{

		}

		public function after_action()
		{

		}

		public function edit(string $user,int $id)
        {

            return $this->view('edit',compact('user','id'));
        }
	}

}
