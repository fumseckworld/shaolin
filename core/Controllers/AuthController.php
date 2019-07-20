<?php

namespace Shaolin\Controllers { 

	use Imperium\Controller\Controller;

	Class AuthController extends Controller
	{

		public function before_action()
		{

		}

		public function after_action()
		{

		}

		public function create_account()
        {
            return $this->auth()->create();
        }
		public function login()
        {
            return $this->auth()->login();
        }

		public function connexion()
        {
            $form = connexion('create_account','login','username','lastame','email','password','confirm password','create account','connexion');

            return $this->view('register',compact('form'));
        }
	}

}
