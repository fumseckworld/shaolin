<?php

namespace App\Controllers {

    use App\Forms\Form;
    use App\Forms\LoginForm;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Collection\Collect;
    use Imperium\Container\Ioc;
    use Imperium\Database\Query\Sql;
    use Imperium\Html\Form\Generator\FormGenerator;
    use Imperium\Http\Controller\Controller;
    use Imperium\Http\Request\Request;
    use Imperium\Http\Response\Response;

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
            return $this->response()->setContent($this->form(LoginForm::class));
        }
    }
}
