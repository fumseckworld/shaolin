<?php

namespace App\Controllers {
	
	use App\Models\Users;
	use Imperium\Controller\Controller;
	use Imperium\Exception\Kedavra;
	use Symfony\Component\HttpFoundation\Response;
	use Twig\Error\LoaderError;
	use Twig\Error\RuntimeError;
	use Twig\Error\SyntaxError;
	
	Class GitController extends Controller
	{
		
		public function before_action()
		{
		
		}

		public function after_action()
		{
		
		}
		
		public function add_repository()
		{
			return $this->view('a',func_get_args());
		}
		
		/**
		 * @throws Kedavra
		 * @throws LoaderError
		 * @throws RuntimeError
		 * @throws SyntaxError
		 * @return Response
		 */
	    public function repositories()
        {
        	$users = Users::paginate([$this,'records'],get('page',1),10);
		
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
