<?php

namespace Nol\Http\Controller {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Collection\Collect;
    use Nol\Configuration\Config;
    use Nol\Database\Connection\Connect;
    use Nol\Database\Model\Crud;
    use Nol\Database\Model\Model;
    use Nol\Database\Query\Sql;
    use Nol\Database\Table\Table;
    use Nol\Environment\Env;
    use Nol\Exception\Kedavra;
    use Nol\Html\Form\Generator\FormGenerator;
    use Nol\Http\Request\Request;
    use Nol\Http\Response\Response;
    use Nol\Http\Views\View;
    use Nol\Messages\Flash\Flash;
    use Nol\Security\Hashing\Hash;
    use Nol\Session\Session;
    use stdClass;

    abstract class Controller
    {
        protected static string $layout = 'layout';
        protected static string $directory = '';

        /**
         *
         * Get the query builder.
         *
         * @param string $table The table name.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Sql
         *
         */
        final public function sql(string $table): Sql
        {
            return (new Sql())->for($this->connect())->from($table);
        }

        /**
         *
         * create read update or delete record.
         *
         * @param class-string $class
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Crud
         *
         */
        final public function crud(string $class): Crud
        {
            return $this->app($class);
        }

        /**
         *
         * Get an instance of table.
         *
         * @param string $table The table name.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Table
         *
         */
        final public function table(string $table = ''): Table
        {
            return def($table) ?
                (new Table($this->connect()->env()))->from($table) :
                new Table($this->connect()->env());
        }

        /**
         *
         * Generate the form.
         *
         * @param class-string $form The form class name.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function form(string $form): string
        {
            return $this->app($form)->display();
        }

        /**
         * @param string   $class
         * @param stdClass $data
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @return string
         */
        final public function each(string $class, stdClass $data): string
        {
            return $this->app($class)->each($data, false);
        }

        /**
         *
         * Search the values in the table.
         *
         * @param class-string   $class     The search class.
         * @param int    $page      The current page.
         * @param string ...$values The values to search.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        public function searchFormResults(string $class, int $page, string ...$values)
        {
            $html = '';
            foreach ($values as $value) {
                $html .= $this->search($class, $value, $page);
            }
            return $html;
        }

        /**
         *
         * Apply the form request on success.
         *
         * @param string  $form    The form class name.
         * @param Request $request The user request.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        final public function apply(string $form, Request $request): Response
        {
            return $this->app($form)->apply($request);
        }

        /**
         *
         * Get an instance of the response.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        final public function response(): Response
        {
            return $this->app('response');
        }

        /**
         *
         * Get an instance of environment.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Env
         *
         */
        final public function env(): Env
        {
            return $this->app('env');
        }

        /**
         *
         * Get the session instance.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Session
         *
         */
        final public function session(): Session
        {
            return $this->app('session');
        }

        /**
         *
         * Get an instance of the collection.
         *
         * @param array|object $data The data to use.
         *
         * @return Collect
         *
         */
        final public function collect($data): Collect
        {
            return collect($data);
        }

        /**
         *
         * Render a view
         *
         * @param string        $view        The view name.
         * @param string        $title       The view title.
         * @param string        $description The view description.
         * @param array<string> $keywords    The view keywords
         * @param array         $args        The views args.
         * @param string        $robots
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @return Response
         */
        final public function view(
            string $view,
            string $title,
            string $description,
            array $keywords,
            array $args = [],
            string $robots = INDEX_PAGE
        ): Response {
            if (not_def(static::$directory)) {
                $dir = str_replace('Controller', '', collect(explode('\\', get_called_class()))->last());
            } else {
                $dir = static::$directory;
            }
            return (new View($dir, $view, $title, $description, static::$layout, $keywords, $robots, $args))->send();
        }

        /**
         *
         * Save a flash message.
         *
         * @param string $message The message to display.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return void
         *
         */
        final public function flash(string $message): void
        {
            if (not_cli()) {
                Flash::set($message);
            }
        }

        /**
         *
         * Get a config value.
         *
         * @param string $file The config filename.
         * @param string $key  The config key.
         *
         * @throws Kedavra
         *
         * @return mixed
         *
         */
        final public function conf(string $file, string $key)
        {
            return (new Config($file, $key))->get();
        }

        /**
         *
         * Secure a string.
         *
         * @param string $value The value to hash.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function hash(string $value): string
        {
            return (new Hash($value))->generate();
        }

        /**
         *
         * Check if data is valid.
         *
         * @param string $data
         * @param string $value The value to analyse.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return bool
         *
         */
        final public function valid(string $data, string $value): bool
        {
            return (new Hash($data))->valid($value);
        }

        /**
         *
         * Get and instance of connect.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Connect
         *
         */
        final public function connect(): Connect
        {
            return $this->app('connect');
        }

        /**
         *
         * Search the value in a base or in a table using the search class.
         *
         * @param class-string  $class   The search class name.
         * @param string $value The search value.
         * @param int    $page  The current page.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function search(string $class, string $value, int $page = 1): string
        {
            return $this->app($class)->search(
                $value,
                $this->connect()->env(),
                $page
            );
        }

        /**
         *
         * @param string $class
         * @param string $value
         * @param string $column
         * @param string $resultsTitle
         * @param string $differentTitle
         * @param int    $page
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function whereAndDifferent(
            string $class,
            string $value,
            string $column,
            string $resultsTitle,
            string $differentTitle,
            int $page
        ): string {
            return $this->app($class)->whereAndDifferent(
                $value,
                $column,
                $resultsTitle,
                $differentTitle,
                $this->connect()->env(),
                $page
            );
        }

        /**
         *
         * Get the generated form for search a value.
         *
         * @param string $class The search class.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function searchForm(string $class): string
        {
            return $this->app($class)->form(new FormGenerator());
        }

        /**
         *
         * Get an instance of the model.
         *
         * @param class-string $model The model to load.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Model
         *
         */
        final public function model(string $model): Model
        {
            return $this->app($model);
        }

        /**
         *
         * Get a container value.
         *
         * @param string $key The container key.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return mixed
         *
         */
        final public function app(string $key)
        {
            return app($key);
        }
    }
}
