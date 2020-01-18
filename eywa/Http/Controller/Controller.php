<?php

declare(strict_types=1);
namespace Eywa\Http\Controller {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Application\App;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Response\Response;
    use Eywa\Http\View\View;

    abstract class Controller extends App
    {

        /**
         * @param string $table
         *
         * @return Sql
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Exception
         */
        public function sql(string $table): Sql
        {
            return new Sql(ioc(Connect::class)->get(),$table);
        }

        /**
         *
         * Initialise a view
         *
         * @param string $view
         * @param string $title
         * @param string $description
         * @param array $args
         * @param string $layout
         *
         * @return Response
         *
         * @throws Kedavra
         */
        public function view(string $view,string $title,string $description,array $args = [],string $layout = 'layout.php'): Response
        {
            return new Response(new View($view,$title,$description,$args,$layout));
        }

    }
}