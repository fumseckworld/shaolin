<?php

namespace App\Controllers {

    use App\Search\ArticlesSearch;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Collection\Collect;
    use Nol\Container\Ioc;
    use Nol\Database\Query\Sql;
    use Nol\Html\Form\Generator\FormGenerator;
    use Nol\Http\Controller\Controller;
    use Nol\Http\Request\Request;
    use Nol\Http\Response\Response;

    class WelcomeController extends Controller
    {
        /**
         * @var FormGenerator
         */
        private FormGenerator $formGenerator;
        /**
         * @var Collect
         */
        private Collect $collect;
        /**
         * @var Sql
         */
        private Sql $sql;

        /**
         * @var Ioc
         */
        private Ioc $ioc;
        /**
         * @var Request
         */
        private Request $request;

        public function __construct(
            FormGenerator $formGenerator,
            Collect $collect,
            Sql $sql,
            Ioc $ioc,
            Request $request
        ) {
            $this->formGenerator = $formGenerator;
            $this->collect = $collect;
            $this->sql = $sql;
            $this->ioc = $ioc;
            $this->request = $request;
        }

        /**
         * @param Request $request
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @return Response
         */
        final public function home(Request $request): Response
        {
            return $this->view('home', 'welcome', 'a super website', ['judo', 'art martial']);
        }

        /**
         *
         * @param Request $request
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        public function searchArticles(Request $request): Response
        {
            $html = $this->searchFormResults(
                ArticlesSearch::class,
                $request->args()->int('page', 1),
                $request->request()->get('created_at')
            );
            return $this->view(
                'results',
                'results',
                'get the result of search',
                ['search', 'results'],
                compact('html')
            );
        }

        public function readArticles(Request $request): Response
        {
            $article = $this->search(ArticlesSearch::class, $request->request()->get('title'));
            return $this->view('show', 'read', 'read and article', ['article', 'read'], compact('article'));
        }

        /**
         * @param Request $request
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        public function found(Request $request): Response
        {
            $results = $this->search(
                ArticlesSearch::class,
                $request->args()->get('value', ''),
                $request->args()->int('page', 1)
            );
            $form = $this->searchForm(ArticlesSearch::class);

            return $this->view(
                'search',
                'search',
                sprintf(
                    'The result for %s',
                    $request->args()->string('value')
                ),
                ['search'],
                compact('results', 'form')
            );
        }

        /**
         * @param Request $request
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @return Response
         */
        public function run(Request $request)
        {
            return $this->home($request);
        }
    }
}
