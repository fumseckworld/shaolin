<?php

namespace Shaolin\Controllers {


    use Imperium\Controller\Controller;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\Patronus\Patronum;
    use Imperium\Request\Request;
    use Imperium\Versioning\Git\Git;


    Class GitController extends Controller
	{

	    private $prefix = "depots";

        public function before_action()
        {

        }

        public function after_action()
		{

		}

		public function close_todo(string $owner,string $reopsitory,int $id)
        {
            return $this->git("{$this->prefix}/$owner/$reopsitory",$owner)->close_todo($id);
        }
		public function add_repository()
        {

            Dir::checkout(ROOT . DIRECTORY_SEPARATOR . $this->prefix);

            return Git::create($this->request()->get('repository'),logged_user(),$this->request()->get('description'),$this->request()->get('email')) ? $this->back('Repository created') : $this->back('Fail',false);
        }
        public function download_archive(string $repo,string $owner,string $tag,string $ext)
        {
            return $this->download($this->git("{$this->prefix}/$owner/$repo",$owner)->generate_archives($ext,$tag));
        }
		public function send_bugs()
        {
            return $this->git($this->request()->get('repository'),'')->send_bug();

        }
		public function stars(string $repository,string $owner)
        {
            return $this->git("{$this->prefix}/$owner/$repository",$owner)->stars();
        }
		public function get_app(string $repository,string $owner)
        {
            return $this->git("{$this->prefix}/$owner/$repository",$owner)->download();
        }

		public function show(string $owner,string $repo,string $branch)
        {

            $repository = $this->git("{$this->prefix}/$owner/$repo",$owner)->save()->git('','',$branch);
            return $this->view('repository',compact('repository'));
        }

        public function show_file(string $owner,string $repository,string $branch,string $file)
        {

            $tree = $this->git("{$this->prefix}/$owner/$repository",$owner)->git('',$file,$branch);
            return $this->view('dirs',compact('tree'));
        }
        public function tree(string $owner,string $repository,string $branch,string $tree)
        {


            $tree = $this->git("{$this->prefix}/$owner/$repository",$owner)->git($tree,'',$branch);
            return $this->view('dirs',compact('tree'));
        }

		public function compare()
        {
            $fist = Request::get('first');
            $second = Request::get('second');

            return equal($fist,$second) ? $this->git->news() : $this->git->compare($fist,$second);


        }
		public function commit(string $repo,string $sha1)
        {
            $git = $this->git($repo,'');
            return $this->response($git->modified($sha1))->send();

        }

        /**
         * @return string
         * @throws Kedavra
         * @throws \DI\DependencyException
         * @throws \DI\NotFoundException
         */
		public function repositories()
        {

            $repo = display_repositories();

            return $this->view('repositories',compact('repo'));

        }

	}

}
