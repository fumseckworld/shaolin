<?php

namespace Imperium\Http\Controller {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Collection\Collect;
    use Imperium\Configuration\Config;
    use Imperium\Database\Connection\Connect;
    use Imperium\Database\Query\Sql;
    use Imperium\Database\Table\Table;
    use Imperium\Environment\Env;
    use Imperium\Exception\Kedavra;
    use Imperium\Http\Request\Request;
    use Imperium\Http\Response\Response;
    use Imperium\Http\Views\View;
    use Imperium\Messages\Flash\Flash;
    use Imperium\Security\Hashing\Hash;
    use Imperium\Session\Session;

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
            return $this->app('sql')->from($table);
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
            return def($table) ? $this->app('table')->from($table) : $this->app('table');
        }

        /**
         *
         * Generate the form.
         *
         * @param string $form The form class name.
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
         * @param string $view        The view name.
         * @param string $title       The view title.
         * @param string $description The view description.
         * @param array<string>  $keywords   The view keywords
         * @param array  $args        The views args.
         * @param string $robots
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
            Flash::set($message);
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
