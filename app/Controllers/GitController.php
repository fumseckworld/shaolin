<?php

namespace App\Controllers {
    
    use Imperium\Controller\Controller;

    Class GitController extends Controller
	{

	    public function repositories()
        {
		
            return $this->view('repositories');
        }
	}

}
