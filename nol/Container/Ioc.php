<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace Nol\Container {

    use Closure;
    use DI\Container;
    use DI\ContainerBuilder;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use InvalidArgumentException;
    use Nol\Cache\ZenCache;
    use Nol\Database\Connection\Connect;
    use Nol\Database\Query\Sql;
    use Nol\Environment\Env;
    use Nol\Http\Request\Request;
    use Nol\Http\Response\Response;
    use Nol\Session\Session;

    /**
     *
     * Represent the core of the container of dependencies injection.
     *
     * This package contains all useful methods to manage the container content.
     *
     * It's the parent class of container class.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Container\Ioc
     * @version 12
     *
     *
     */
    final class Ioc
    {

        private static ?Container $container = null;

        /**
         *
         * Get an instance of an object inside the container.
         *
         * @param string $key The container key.
         *
         * @throws NotFoundException
         * @throws Exception
         * @throws DependencyException
         *
         * @return mixed
         *
         */
        final public function get(string $key)
        {
            return $this->container()->get($key);
        }

        /**
         *
         * Define an object or a value in the container.
         *
         * @param string $key   The container key.
         * @param mixed  $value The container value.
         *
         * @throws Exception
         *
         * @return Ioc
         *
         */
        final public function set(string $key, $value): Ioc
        {
            $this->container()->set($key, $value);
            return $this;
        }

        /**
         *
         * Call the given function using the given parameters
         *
         * Missing parameters will be resolved from the container.
         *
         * @param Closure $callback The callback to call.
         * @param array   $args     The callback arguments.
         *
         * @throws Exception
         *
         * @return mixed
         *
         */
        final public function call(Closure $callback, array $args = [])
        {
            return $this->container()->call($callback, $args);
        }

        /**
         *
         * Make always a new instance
         *
         * @param string $key  The container key.
         * @param array  $args All parameters.
         *
         * @throws DependencyException Error while resolving the entry.
         * @throws NotFoundException No entry found for the given name.
         * @throws Exception
         * @throws InvalidArgumentException The name parameter must be of type string.
         *
         * @return object
         *
         */
        final public function make(string $key, array $args = []): object
        {
            return $this->container()->make($key, $args);
        }


        /**
         *
         * Check if a key exist in the container.
         *
         * @param string $key The key to check
         *
         * @throws Exception
         *
         * @return boolean
         *
         */
        final public function has(string $key): bool
        {
            return $this->container()->has($key);
        }

        /**
         *
         * Return the instance of the container.
         *
         * @throws Exception
         *
         **@return Container
         */
        final public function ioc(): Container
        {
            return $this->container();
        }

        /**
         *
         * Build the container.
         *
         * @throws Exception
         *
         * @return  Container
         *
         */
        final private function container(): Container
        {

            if (is_null(static::$container)) {
                $c = new ContainerBuilder();
                $c->addDefinitions(
                    base(
                        nol('container-dir', 'ioc'),
                        'admin.php'
                    ),
                    base(
                        nol('container-dir', 'ioc'),
                        'web.php'
                    )
                );
                $c->useAnnotations(true);
                $c->useAutowiring(true);
                $c = $c->build();
                $c->set('sql', new Sql());
                $c->set('response', new Response());
                $c->set('request', new Request());
                $c->set('connect', (new Connect()));
                $c->set('env', new Env());
                $c->set('cache', new ZenCache());
                $c->set('session', new Session());

                $c->set('web', (new Sql())->for(connect('sqlite', base('routes', 'web.db')))->from('routes'));
                $c->set('todo', (new Sql())->for(connect('sqlite', base('routes', 'todo.db')))->from('routes'));
                $c->set('admin', (new Sql())->for(connect('sqlite', base('routes', 'admin.db')))->from('routes'));

                $c->set('app-directory', nol('app-directory', 'app'));
                $c->set('pagination-results-text', nol('pagination-results-text', 'records has been found'));
                $c->set('pagination-class', nol('pagination-class', 'pagination'));

                $c->set('views-directory', nol('views-directory', 'Views'));

                $c->set('not-found-controller-name', nol('not-found-controller-name', 'NotFoundController'));

                $c->set('not-found-action-name', nol('not-found-action-name', 'notFound'));

                $c->set('db-directory', nol('db-directory', 'db'));

                $c->set('tests-directory', nol('tests-directory', 'tests'));

                $c->set('app-namespace', nol('app-namespace', 'App'));

                $c->set('base-namespace', nol('base-namespace', 'Evolution'));

                $c->set('tests-namespace', nol('tests-namespace', 'Testing'));
                $c->set('server-port', nol('server-port', '3000'));

                $c->set('form-submit-classname', nol('form-submit-classname', 'form-button form-submit'));

                $c->set('form-separator-classname', nol('form-separator-classname', 'form-group'));

                $c->set('form-input-classname', nol('form-input-classname', 'form-input'));

                $c->set(
                    'models-dirname',
                    nol(
                        'app-models-directory',
                        'Models'
                    )
                );

                $c->set('app-dirname', $c->get('app-directory'));

                $c->set(
                    'views-dirname',
                    nol(
                        'app-views-directory',
                        'Views'
                    )
                );

                $c->set(
                    'public-dirname',
                    nol(
                        'public-directory',
                        'web'
                    )
                );

                $c->set(
                    'db-dirname',
                    nol(
                        'db-base-directory',
                        'db'
                    )
                );

                $c->set(
                    'emails-dirname',
                    nol(
                        'app-emails-directory',
                        'Emails'
                    )
                );

                $c->set(
                    'cache-dirname',
                    nol(
                        'cache-directory',
                        'cache'
                    )
                );

                $c->set(
                    'controllers-dirname',
                    nol(
                        'app-controllers-directory',
                        'Controllers'
                    )
                );

                $c->set(
                    'forms-dirname',
                    nol(
                        'app-forms-directory',
                        'Forms'
                    )
                );


                $c->set(
                    'emails-dirname',
                    nol(
                        'app-emails-directory',
                        'Emails'
                    )
                );

                $c->set(
                    'consoles-dirname',
                    nol(
                        'app-consoles-directory',
                        'Consoles'
                    )
                );

                $c->set(
                    'validators-dirname',
                    nol(
                        'app-validators-directory',
                        'Validators'
                    )
                );

                $c->set(
                    'search-dirname',
                    nol(
                        'app-search-directory',
                        'Search'
                    )
                );

                $c->set(
                    'translations-dirname',
                    nol(
                        'translations-directory',
                        'po'
                    )
                );
                $c->set(
                    'migrations-dirname',
                    nol(
                        'db-migrations-directory',
                        'Migrations'
                    )
                );

                $c->set(
                    'seeds-dirname',
                    nol(
                        'db-seeds-directory',
                        'Seeds'
                    )
                );

                $c->set('tests-dirname', $c->get('tests-directory'));

                $c->set('tests-path', base($c->get('db-directory'), $c->get('tests-dirname')));

                $c->set('migrations-path', base($c->get('db-directory'), $c->get('migrations-dirname')));

                $c->set('seeds-path', base($c->get('db-directory'), $c->get('seeds-dirname')));

                $c->set('models-path', base($c->get('app-directory'), $c->get('models-dirname')));

                $c->set('views-path', base($c->get('app-directory'), $c->get('views-dirname')));

                $c->set('controllers-path', base($c->get('app-directory'), $c->get('controllers-dirname')));

                $c->set('emails-path', base($c->get('app-directory'), $c->get('emails-dirname')));

                $c->set('forms-path', base($c->get('app-directory'), $c->get('forms-dirname')));

                $c->set('consoles-path', base($c->get('app-directory'), $c->get('consoles-dirname')));

                $c->set('validators-path', base($c->get('app-directory'), $c->get('validators-dirname')));

                $c->set('search-path', base($c->get('app-directory'), $c->get('search-dirname')));

                $c->set('cache-path', base($c->get('cache-dirname')));

                $c->set('translations-path', base($c->get('translations-dirname')));

                static::$container = $c;
            }
            return static::$container;
        }
    }
}
