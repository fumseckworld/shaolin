<?php

declare(strict_types=1);
namespace Eywa\Http\Controller {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Application\App;
    use Eywa\Database\Connection\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;

    use Eywa\Http\View\View;

    abstract class Controller extends App
    {

        /**
         * @param string $table
         *
         * @return Sql
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @return View
         *
         * @throws Kedavra
         */
        public function view(string $view,string $title,string $description,array $args = [],string $layout = 'layout.php'): View
        {
            return new View($view,$title,$description,$args,$layout);
        }

        /**
         *
         * Get a $_GET value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function get(string $key,$default = null)
        {
            return htmlentities(request()->query->get($key,$default),ENT_QUOTES);
        }

        /**
         *
         * Get a $_POST value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function post(string $key,$default = null)
        {
            return htmlentities(request()->request->get($key,$default),ENT_QUOTES);
        }

        /**
         *
         * Get a $_COOKIE value
         *
         * @param string $key
         * @param null $default
         * @return mixed
         */
        public function cookie(string $key,$default = null)
        {
            return request()->cookies->get($key,$default);
        }
    }
}