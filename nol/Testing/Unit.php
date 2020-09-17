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

namespace Nol\Testing {

    use Closure;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Nol\Database\Found\Search;
    use Nol\Database\Model\Model;
    use Nol\Database\Query\Sql;
    use Nol\Exception\Kedavra;
    use Nol\Http\Response\JsonResponse;
    use Nol\Http\Response\Response;
    use Nol\Http\Routing\Route;
    use PHPUnit\Framework\MockObject\MockBuilder;
    use PHPUnit\Framework\TestCase;

    /**
     *
     * Represent all method used to test the application.
     *
     * This packages contains all useful methods to write tests more simply.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Testing\Unit
     * @version 12
     *
     */
    abstract class Unit extends TestCase
    {
        /**
         *
         * Asserts that a variable is null.
         *
         * @param mixed[] $actual The data to check.
         *
         * @return Unit
         *
         */
        final public function notDef(...$actual): self
        {
            foreach ($actual as $value) {
                $this->assertNull($value, _('The data are not equal to null'));
            }
            return $this;
        }

        /**
         * @param string $key
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

        /**
         *
         * Get an instance of a model.
         *
         * @param class-string $class The model to load
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Model
         *
         */
        final public function model(string $class): Model
        {
            return $this->app($class);
        }

        /**
         *
         * Get an instance of the query builder.
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
            return (new Sql())->from($table)->for($this->app('connect')->env());
        }

        /**
         *
         * Get an instance of a search class.
         *
         * @param class-string $class The search class name.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Search
         *
         */
        final public function search(string $class): Search
        {
            return $this->app($class);
        }

        /**
         *
         * Get an instance of the response.
         *
         * @return Response
         */
        final public function response(): Response
        {
            return new Response();
        }

        /**
         * @param string $data      All data.
         * @param string ...$values the values to check.
         *
         * @return Unit
         *
         */
        final public function contains(string $data, string ...$values): Unit
        {
            foreach ($values as $value) {
                $this->assertStringContainsString($value, $data, _('The expected value has not been found'));
            }
            return $this;
        }

        /**
         *
         * Send a json response.
         *
         * @param array $data The json data.
         *
         *
         * @return Response
         *
         */
        final public function json(array $data): Response
        {
            return (new JsonResponse($data))->send();
        }

        /**
         *
         * Get an instance of the mock builder.
         *
         * @param class-string<object> $class The class name to mock
         *
         * @return MockBuilder
         *
         */
        final public function mock(string $class): MockBuilder
        {
            return $this->getMockBuilder($class);
        }

        /**
         *
         * Asserts that a condition is true.
         *
         * @param mixed $conditions The condition to check.
         *
         * @return Unit
         *
         */
        final public function success(...$conditions): self
        {
            foreach ($conditions as $condition) {
                $this->assertTrue($condition, _('The condition must return true but return false'));
            }
            return $this;
        }

        /**
         *
         * Visit a page
         *
         * @param string $url    the url to visit.
         * @param string $method The http method to access at the page.
         *
         * @throws NotFoundException
         * @throws Exception
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws DependencyException
         * @return Response
         *
         */
        final public function visit(string $url, string $method = 'GET'): Response
        {
            return app('response')->from('cli', $url, strtoupper($method))->get();
        }


        /**
         *
         * Test a route.
         *
         * @param string $controller The controller name.
         * @param string $action     The controller action name
         * @param array  $args       The action args.
         *
         * @throws Kedavra
         *
         * @return Route
         *
         */
        final public function route(string $controller, string $action, array $args = []): Route
        {
            return new Route($controller, $action, $args);
        }

        /**
         * Undocumented function
         *
         * @param class-string<\Throwable>  $expected  $expected The expected exception.
         * @param string  $message The expected message.
         * @param Closure $closure The function to test code.
         *
         * @return Unit
         *
         */
        final public function throw(string $expected, string $message, Closure $closure): self
        {
            $this->expectException($expected);
            $this->expectExceptionMessage($message);
            call_user_func($closure);
            return $this;
        }

        /**
         *
         * Asserts that a condition is false.
         *
         * @param mixed $conditions The condition to check.
         *
         * @return Unit
         *
         */
        final public function failure(...$conditions): self
        {
            foreach ($conditions as $condition) {
                $this->assertFalse($condition, _('The condition must return false but return true'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type bool.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function boolean(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsBool($value, _('The result is not of the boolean type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type bool.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notBoolean(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotBool($value, _('The result is of the boolean type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a variable is of type int.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function int(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsInt($value, _('The result is not of the int type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type int.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notInt(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotInt($value, _('The result is of the int type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type numeric.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function num(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNumeric($value, _('The result is not of the numeric type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type numeric.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notNum(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotNumeric($value, _('The result is of the numeric type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type callable.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function callable(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsCallable($value, _('The result is not of the callable type'));
            }
            return $this;
        }

        /**
         *
         * Assert that a variable is not of type callable.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notCallable(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotCallable($value, _('The result is of the callable type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type float.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function float(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsFloat($value, _('The result is not of the float type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type float.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notFloat(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotFloat($value, _('The result is of the float type'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type iterable.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function iterable(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsIterable($value, _('The result is not of the iterable type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type iterable.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notIterable(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotIterable($value, _('The result is of the iterable type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a variable is of type array.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function isArray(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsArray($value, _('The result is not of the array type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type array.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notArray(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotArray($value, _('The result is of the array type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a variable is of type object.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function object(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsObject($value, _('The result is not of the object type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type object.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function notObject(...$values): self
        {
            foreach ($values as $value) {
                $this->assertIsNotObject($value, _('The result is of the object type'));
            }
            return $this;
        }


        /**
         *
         * Asserts that actual is empty.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function empty(...$values): self
        {
            foreach ($values as $value) {
                $this->assertEmpty($value, _('The result is not empty'));
            }
            return $this;
        }


        /**
         *
         * Asserts that actual is not empty.
         *
         * @param mixed $values The value to check.
         *
         * @return Unit
         *
         */
        final public function def(...$values): self
        {
            foreach ($values as $value) {
                $this->assertNotEmpty($value, _('The result is empty'));
            }
            return $this;
        }

        /**
         *
         * Asserts that values contains a value.
         *
         * @param iterable $data   All values.
         * @param mixed    $values all values to check.
         *
         * @return Unit
         *
         */
        final public function exist(iterable $data, ...$values): self
        {
            foreach ($values as $value) {
                $this->assertContains($value, $data, _('The value has not been found in all values'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a values does not contain a value.
         *
         * @param iterable $data   All values.
         * @param mixed    $values The values to check.
         *
         * @return Unit
         *
         */
        final public function notExist(iterable $data, ...$values): self
        {
            foreach ($values as $value) {
                $this->assertNotContains($value, $data, _('The value has been found in all values'));
            }
            return $this;
        }

        /**
         *
         * Asserts that an array does not have a specified key.
         *
         * @param array $array The array to parse.
         * @param mixed $keys  The keys to verify the no existence.
         *
         * @return Unit
         *
         */
        final public function notHas(array $array, ...$keys): self
        {
            foreach ($keys as $key) {
                $this->assertArrayNotHasKey($key, $array, _('The key has been found in the array'));
            }
            return $this;
        }

        /**
         *
         * Asserts that an array has a specified key.
         *
         * @param array $array The array to parse.
         * @param mixed $keys  The keys to verify the existence.
         *
         * @return Unit
         *
         */
        final public function has(array $array, ...$keys): self
        {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $array, _('The key has not been found in the array'));
            }
            return $this;
        }

        /**
         *
         * Asserts that two variables are equal.
         *
         * @param mixed $expected The expected value.
         * @param mixed $actual   The actual value.
         *
         * @return Unit
         *
         */
        final public function identical($expected, $actual): self
        {
            $this->assertEquals($expected, $actual, _('The actual value is not identic to the expected value'));
            return $this;
        }

        /**
         *
         * Asserts that two variables are not equal.
         *
         * @param mixed $expected The expected value.
         * @param mixed $actual   The actual value.
         *
         * @return Unit
         *
         */
        final public function different($expected, $actual): self
        {
            $this->assertNotEquals($expected, $actual, _('The actual value is not identic to the expected value'));
            return $this;
        }

        /**
         *
         * Asserts that a haystack contains only values of a given type.
         *
         * @param string   $type   The type to check.
         * @param iterable $values All values.
         * @param boolean  $native The type.
         *
         * @return Unit
         *
         */
        final public function only(string $type, iterable $values, bool $native = null): self
        {
            $this->assertContainsOnly(
                $type,
                $values,
                $native,
                _('The type has not been found in all values')
            );
            return $this;
        }


        /**
         *
         * Asserts that a haystack does not contain only values of a given type.
         *
         * @param string   $type   The type to check.
         * @param iterable $values All values.
         * @param boolean  $native The type.
         *
         * @return Unit
         *
         */
        final public function notOnly(string $type, iterable $values, bool $native = null): self
        {
            $this->assertNotContainsOnly(
                $type,
                $values,
                $native,
                _('The type has been found in all values')
            );
            return $this;
        }

        /**
         *
         * Asserts that a variable is of a given type.
         *
         * @param class-string<object> $instance
         * @param mixed $values The actual instance.
         *
         * @return Unit
         */
        final public function is(string $instance, ...$values): self
        {
            foreach ($values as $value) {
                $this->assertInstanceOf(
                    $instance,
                    $value,
                    _('The instance is not an instance of the expected class')
                );
            }

            return $this;
        }


        /**
         *
         * Asserts that a variable is not of a given type.
         *
         * @param mixed $actual The actual instance.
         * @param class-string<object> $instances
         *
         * @return Unit
         *
         */
        final public function not($actual, ...$instances): self
        {
            foreach ($instances as $instance) {
                $this->assertNotInstanceOf(
                    $instance,
                    $actual,
                    _('The instance is not an instance of the expected class')
                );
            }
            return $this;
        }

        /**
         *
         * Asserts that a class has a specified attribute.
         *
         * @param string $class      The class name.
         * @param mixed  $attributes the attribute to check
         *
         * @return Unit
         *
         */
        final public function hasAttribute(string $class, ...$attributes): self
        {
            foreach ($attributes as $attribute) {
                $this->assertClassHasAttribute($attribute, $class, _('The class attribute has not been found'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a class does not have a specified attribute.
         *
         * @param string $class      The class name.
         * @param mixed  $attributes The attributes names.
         *
         * @return self
         *
         */
        final public function hasNotAttribute(string $class, ...$attributes): self
        {
            foreach ($attributes as $attribute) {
                $this->assertClassNotHasAttribute($attribute, $class, _('The class attribute has been found'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a class has a specified static attribute.
         *
         * @param string $class      The class name.
         * @param mixed  $attributes The attributes names.
         *
         * @return Unit
         *
         */
        final public function hasStatic(string $class, ...$attributes): self
        {
            foreach ($attributes as $attribute) {
                $this->assertClassHasStaticAttribute($attribute, $class, _('The attribute has not been found'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a class does not have a specified static attribute.
         *
         * @param string $class      The class name.
         * @param mixed  $attributes the attributes names.
         *
         * @return Unit
         *
         */
        final public function hasNotStatic(string $class, ...$attributes): self
        {
            foreach ($attributes as $attribute) {
                $this->assertClassNotHasStaticAttribute($attribute, $class, _('The attribute has been found'));
            }
            return $this;
        }

        /**
         *
         * Asserts the number of elements of an array, Countable or Traversable.
         *
         * @param int      $expected The expected number.
         * @param iterable $value    The data to count.
         *
         * @return Unit
         *
         */
        final public function sum(int $expected, iterable $value): self
        {
            $this->assertCount($expected, $value, _('The results not match te expected value'));
            return $this;
        }

        /**
         *
         * Asserts that directories exists.
         *
         * @param mixed ...$directories The directories name to check.
         *
         * @return Unit
         *
         */
        final public function directoriesExist(...$directories): self
        {
            foreach ($directories as $directory) {
                $this->assertDirectoryExists($directory, _('The directory not exists'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a directory does not exist.
         *
         * @param mixed ...$directories The directories to check.
         *
         * @return Unit
         *
         */
        final public function directoriesNotExist(...$directories): self
        {
            foreach ($directories as $directory) {
                $this->assertDirectoryNotExists($directory, _('The directory has been found'));
            }
            return $this;
        }

        /**
         *
         * Asserts that a directory exists and is readable.
         *
         * @param mixed ...$directories The directories to check.
         *
         * @return Unit
         *
         */
        final public function directoriesReadable(...$directories): self
        {
            foreach ($directories as $directory) {
                $this->assertDirectoryIsReadable($directory, _('The directory is not readable'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a directory exists and is not readable.
         *
         * @param mixed ...$directories The directories to check.
         *
         * @return Unit
         *
         */
        final public function directoriesNotReadable(...$directories): self
        {
            foreach ($directories as $directory) {
                $this->assertDirectoryNotIsReadable($directory, _('The directory is readable'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a directory exists and is writable.
         *
         * @param mixed ...$directories The directories to check.
         *
         * @return Unit
         *
         */
        final public function directoriesWritable(...$directories): self
        {
            foreach ($directories as $directory) {
                $this->assertDirectoryIsWritable($directory, _('The directory is not writable'));
            }
            return $this;
        }


        /**
         *
         * Asserts that a directory exists and is not writable.
         *
         * @param mixed ...$directories The directories to check.
         *
         * @return Unit
         *
         */
        final public function directoriesNotWritable(...$directories): self
        {
            foreach ($directories as $directory) {
                $this->assertDirectoryNotIsWritable($directory, _('The directory is writable'));
            }
            return $this;
        }
    }
}
