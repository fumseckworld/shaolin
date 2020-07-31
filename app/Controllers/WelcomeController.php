<?php

namespace App\Controllers {

    use Imperium\Collection\Collect;
    use Imperium\Container\Ioc;
    use Imperium\Database\Query\Sql;
    use Imperium\Html\Form\Generator\FormGenerator;
    use Imperium\Http\Request\Request;
    use Imperium\Http\Response\Response;

    class WelcomeController
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
         * @return Response
         */
        final public function run(): Response
        {
            return (new Response())->setContent('ok')->send();
        }
    }
}
