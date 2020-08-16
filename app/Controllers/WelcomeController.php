<?php

namespace App\Controllers {

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
        final public function run(Request $request): Response
        {
            return $this->view('home', 'welcome', 'a super website', ['judo', 'art martial']);
        }
    }
}
