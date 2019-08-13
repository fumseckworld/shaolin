<?php

namespace App\Controllers {
    
    use Imperium\Controller\Controller;
	use Imperium\File\File;
	
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
		
            return $this->view('repositories');
        }
        
        public function commit(string $repo,string $sha1)
		{
			
			return $this->view('commit',compact('repo','sha1'));
		}
	}

}
