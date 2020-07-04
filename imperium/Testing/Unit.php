<?php

namespace Imperium\Testing {


    use PHPUnit\Framework\TestCase;

    abstract class Unit extends TestCase
    {
        /**
         *
         * Asserts that a variable is null.
         *
         * @param mixed $actual The data to check.
         *
         * @return void
         *
         */
        protected function null($actual): void
        {
            $this->assertNull($actual, _('The data are not equal to null'));
        }

        /**
         *
         * Asserts that a condition is true.
         *
         * @param mixed $condition The condition to check.
         *
         * @return void
         *
         */
        protected function success($condition): void
        {
            $this->assertTrue($condition, _('The condition must return true but return false'));
        }

        /**
         *
         * Asserts that a condition is false.
         *
         * @param mixed $condition The condition to check.
         *
         * @return void
         *
         */
        protected function failure($condition): void
        {
            $this->assertFalse($condition, _('The condition must return false but return true'));
        }

        /**
         *
         * Asserts that a variable is of type bool.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function boolean($actual): void
        {
            $this->assertIsBool($actual, _('The result is not of the boolean type'));
        }


        /**
         *
         * Asserts that a variable is not of type bool.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notBoolean($actual): void
        {
            $this->assertIsNotBool($actual, _('The result is of the boolean type'));
        }


        /**
         *
         * Asserts that a variable is of type int.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function int($actual): void
        {
            $this->assertIsInt($actual, _('The result is not of the int type'));
        }

        /**
         *
         * Asserts that a variable is not of type int.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notInt($actual): void
        {
            $this->assertIsNotInt($actual, _('The result is of the int type'));
        }

        /**
         *
         * Asserts that a variable is of type numeric.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function num($actual): void
        {
            $this->assertIsNumeric($actual, _('The result is not of the numeric type'));
        }

        /**
         *
         * Asserts that a variable is not of type numeric.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notNum($actual): void
        {
            $this->assertIsNotNumeric($actual, _('The result is of the numeric type'));
        }

        /**
         *
         * Asserts that a variable is of type callable.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function callable($actual): void
        {
            $this->assertIsCallable($actual, _('The result is not of the callable type'));
        }

        /**
         *
         * Assert that a variable is not of type callable.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notCallable($actual): void
        {
            $this->assertIsNotCallable($actual, _('The result is of the callable type'));
        }

        /**
         *
         * Asserts that a variable is of type float.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function float($actual): void
        {
            $this->assertIsFloat($actual, _('The result is not of the float type'));
        }

        /**
         *
         * Asserts that a variable is not of type float.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notFloat($actual): void
        {
            $this->assertIsNotFloat($actual, _('The result is of the float type'));
        }

        /**
         *
         * Asserts that a variable is of type iterable.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function iterable($actual): void
        {
            $this->assertIsIterable($actual, _('The result is not of the iterable type'));
        }


        /**
         *
         * Asserts that a variable is not of type iterable.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notIterable($actual): void
        {
            $this->assertIsNotIterable($actual, _('The result is of the iterable type'));
        }


        /**
         *
         * Asserts that a variable is of type array.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function isArray($actual): void
        {
            $this->assertIsArray($actual, _('The result is not of the array type'));
        }


        /**
         *
         * Asserts that a variable is not of type array.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notArray($actual): void
        {
            $this->assertIsNotArray($actual, _('The result is of the array type'));
        }


        /**
         *
         * Asserts that a variable is of type object.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function object($actual): void
        {
            $this->assertIsObject($actual, _('The result is not of the object type'));
        }


        /**
         *
         * Asserts that a variable is not of type object.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function notObject($actual): void
        {
            $this->assertIsNotObject($actual, _('The result is of the object type'));
        }


        /**
         *
         * Asserts that actual is empty.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function empty($actual): void
        {
            $this->assertEmpty($actual, _('The result is not empty'));
        }


        /**
         *
         * Asserts that actual is not empty.
         *
         * @param mixed $actual The value to check.
         *
         * @return void
         *
         */
        protected function def($actual): void
        {
            $this->assertNotEmpty($actual, _('The result is empty'));
        }

        /**
         *
         * Asserts that values contains a value.
         *
         * @param mixed $value The value to check the existence
         * @param iterable $values All values.
         *
         * @return void
         *
         */
        protected function exist($value, iterable $values): void
        {
            $this->assertContains($value, $values, _('The value has not been found in all values'));
        }

        /**
         *
         * Asserts that a values does not contain a value.
         *
         * @param mixed $value The value to check the existence
         * @param iterable $values All values.
         *
         * @return void
         *
         */
        protected function notExist($value, iterable $values): void
        {
            $this->assertNotContains($value, $values, _('The value has been found in all values'));
        }

        /**
         *
         * Asserts that an array does not have a specified key.
         *
         * @param mixed $key The key to verify the non existence.
         * @param array $array The array to parse.
         *
         * @return void
         *
         */
        protected function notHas($key, array $array): void
        {
            $this->assertArrayNotHasKey($key, $array, _('The key has been found in the array'));
        }

        /**
         *
         * Asserts that an array has a specified key.
         *
         * @param mixed $key The key to verify the existence.
         * @param array $array The array to parse.
         *
         * @return void
         *
         */
        protected function has($key, array $array): void
        {
            $this->assertArrayHasKey($key, $array, _('The key has not been found in the array'));
        }

        /**
         *
         * Asserts that two variables are equal.
         *
         * @param mixed $expected The expected value.
         * @param mixed $actual   The actual value.
         *
         * @return void
         *
         */
        protected function identic($expected, $actual): void
        {
            $this->assertEquals($expected, $actual, _('The actual value is not identic to the expected value'));
        }

        /**
         *
         * Asserts that two variables are not equal.
         *
         * @param mixed $expected The expected value.
         * @param mixed $actual   The actual value.
         *
         * @return void
         *
         */
        protected function different($expected, $actual): void
        {
            $this->assertNotEquals($expected, $actual, _('The actual value is not identic to the expected value'));
        }

        /**
         *
         * Asserts that a haystack contains only values of a given type.
         *
         * @param string $type The type to check.
         * @param iterable $values All values.
         * @param boolean $native The type.
         *
         * @return void
         *
         */
        protected function only(string $type, iterable $values, bool $native = null): void
        {
            $this->assertContainsOnly(
                $type,
                $values,
                $native,
                _('The type has not been found in all values')
            );
        }


        /**
         *
         * Asserts that a haystack does not contain only values of a given type.
         *
         * @param string $type The type to check.
         * @param iterable $values All values.
         * @param boolean $native The type.
         *
         * @return void
         *
         */
        protected function notOnly(string $type, iterable $values, bool $native = null): void
        {
            $this->assertNotContainsOnly(
                $type,
                $values,
                $native,
                _('The type has been found in all values')
            );
        }

        /**
         *
         * Asserts that a variable is of a given type.
         *
         * @param class-string $instance The expected instance.
         * @param mixed $actual The actual instance.
         *
         * @return void
         */
        protected function is(string $instance, $actual): void
        {
            $this->assertInstanceOf(
                $instance,
                $actual,
                _('The instance is not an instance of the expected class')
            );
        }


        /**
         *
         * Asserts that a variable is not of a given type.
         *
         * @param class-string $instance The expected instance.
         * @param mixed $actual The actual instance.
         *
         * @return void
         *
         */
        protected function not(string $instance, $actual): void
        {
            $this->assertNotInstanceOf(
                $instance,
                $actual,
                _('The instance is not an instance of the expected class')
            );
        }
    }
}
