<?php

namespace ________________________________________________________\Controllers { 

	use Imperium\Controller\Controller;

	Class UsersController extends Controller
	{
	    public function edit(string $name,int $id)
        {
            return $this->view('edit');
        }

	}

}
