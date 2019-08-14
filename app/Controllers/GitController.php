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
			$users = $this->sql('users')->between('id',0,100)->paginate([$this,'records'],get('p',1),10);
            return $this->view('repositories',compact('users'));
        }
        
        public function commit(string $repo,string $sha1)
		{
			
			return $this->view('commit',compact('repo','sha1'));
		}
		
		public function records($key,$item)
		{
			
			return '<header><h2>'.$item->firstname.' '. $item->lastname.'</h2></header><div class="text-center"><a href="mailto:'.$item->email.'"  class="btn-hollow"> contact</a></div>';
			
		}
	}

}
