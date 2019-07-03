<?php

namespace ________________________________________________________\Controllers {


    use Imperium\Controller\Controller;
    use Imperium\Exception\Kedavra;
    use Imperium\Patronus\Patronum;
    use Imperium\Request\Request;
    use Imperium\Versioning\Git\Git;
    use Symfony\Component\HttpFoundation\Response;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    Class GitController extends Controller
	{
        /**
         * @var Git
         */
        private $git;

        public function before_action()
        {

            $this->git = $this->git('depots/willy/imperium','willy');


		}
        public function after_action()
		{

		}

		public function show(string $owner,string $repo)
        {
            $repository = $this->git("depots/$owner/$repo",$owner)->git();
            return $this->view('repository',compact('repository'));
        }
		public function display_dirs(string $repo,string $dir)
        {
            $tree = $this->git->tree($dir);
            return $this->view("dirs",compact('tree'));
        }

        /**
         * @param string $repo
         * @param string $archive
         * @return Response
         * @throws Kedavra
         */
        public function download_archive(string $repo, string $archive)
        {

            return $this->git->download($archive);
        }

		public function compare()
        {
            $fist = Request::get('first');
            $second = Request::get('second');

            return equal($fist,$second) ? $this->git->news() : $this->git->compare($fist,$second);


        }
		public function commit(string $repo,string $sha1)
        {
            return $this->response()->setContent($this->git->modified($sha1))->send();

        }
        /**
         * @return string
         * @throws Kedavra
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
		public function repositories()
        {

            $repo = display_repositories();

            return $this->view('repositories',compact('repo'));

        }

	}

}
