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

namespace Imperium\Container {

    use Closure;
    use DI\Container;
    use DI\ContainerBuilder;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Database\Connection\Connect;
    use Imperium\Database\Query\Sql;
    use Imperium\Database\Table\Table;
    use Imperium\Environment\Env;
    use InvalidArgumentException;

    /**
     *
     * Represent the core of the container of dependencies injection.
     *
     * This package contains all useful methods to manage the container content.
     *
     * It's the parent class of container class.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Container\Ioc
     * @version 12
     *
     * @property Container|null  $container The container instance.
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
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return object
         *
         */
        final public function get(string $key): object
        {
            return $this->container()->get($key);
        }

        /**
         *
         * Define an object or a value in the container.
         *
         * @param string $key The container key.
         * @param mixed  $value The container value.
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
         * @param Closure $callback
         * @param array $args
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
         * @param string $key The container key.
         * @param array  $args All parameters.
         *
         * @throws InvalidArgumentException The name parameter must be of type string.
         * @throws DependencyException Error while resolving the entry.
         * @throws NotFoundException No entry found for the given name.
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
         * @throws InvalidArgumentException
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
         * @return Container
         *
         **/
        final public function ioc(): Container
        {
            return $this->container();
        }

        /**
         *
         * Build the container.
         *
         * @return Container
         *
         */
        final private function container(): Container
        {

            if (is_null(static::$container)) {
                $dir = imperium('container-directory', 'ioc');
                $c = new ContainerBuilder();
                $c->addDefinitions(base($dir, 'admin.php'), base($dir, 'web.php'));
                $c->useAnnotations(true);
                $c->useAutowiring(true);
                $c = $c->build();
                $c->set('sql', new Sql());
                $c->set('table', new Table());
                $c->set('connect', new Connect());
                $c->set('env', new Env());
            
                static::$container = $c;
            }
            return static::$container;
        }
    }
}
