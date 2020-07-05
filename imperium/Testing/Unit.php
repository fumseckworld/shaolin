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
         * @return Unit
         *
         */
        public function null($actual): self
        {
            $this->assertNull($actual, _('The data are not equal to null'));
            return $this;
        }

        /**
         *
         * Asserts that a condition is true.
         *
         * @param mixed $condition The condition to check.
         *
         * @return Unit
         *
         */
        public function success($condition): self
        {
            $this->assertTrue($condition, _('The condition must return true but return false'));
            return $this;
        }

        /**
         *
         * Asserts that a condition is false.
         *
         * @param mixed $condition The condition to check.
         *
         * @return Unit
         *
         */
        public function failure($condition): self
        {
            $this->assertFalse($condition, _('The condition must return false but return true'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type bool.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function boolean($actual): self
        {
            $this->assertIsBool($actual, _('The result is not of the boolean type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type bool.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notBoolean($actual): self
        {
            $this->assertIsNotBool($actual, _('The result is of the boolean type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is of type int.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function int($actual): self
        {
            $this->assertIsInt($actual, _('The result is not of the int type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type int.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notInt($actual): self
        {
            $this->assertIsNotInt($actual, _('The result is of the int type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type numeric.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function num($actual): self
        {
            $this->assertIsNumeric($actual, _('The result is not of the numeric type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type numeric.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notNum($actual): self
        {
            $this->assertIsNotNumeric($actual, _('The result is of the numeric type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type callable.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function callable($actual): self
        {
            $this->assertIsCallable($actual, _('The result is not of the callable type'));
            return $this;
        }

        /**
         *
         * Assert that a variable is not of type callable.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notCallable($actual): self
        {
            $this->assertIsNotCallable($actual, _('The result is of the callable type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type float.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function float($actual): self
        {
            $this->assertIsFloat($actual, _('The result is not of the float type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is not of type float.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notFloat($actual): self
        {
            $this->assertIsNotFloat($actual, _('The result is of the float type'));
            return $this;
        }

        /**
         *
         * Asserts that a variable is of type iterable.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function iterable($actual): self
        {
            $this->assertIsIterable($actual, _('The result is not of the iterable type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type iterable.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notIterable($actual): self
        {
            $this->assertIsNotIterable($actual, _('The result is of the iterable type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is of type array.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function isArray($actual): self
        {
            $this->assertIsArray($actual, _('The result is not of the array type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type array.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notArray($actual): self
        {
            $this->assertIsNotArray($actual, _('The result is of the array type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is of type object.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function object($actual): self
        {
            $this->assertIsObject($actual, _('The result is not of the object type'));
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of type object.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function notObject($actual): self
        {
            $this->assertIsNotObject($actual, _('The result is of the object type'));
            return $this;
        }


        /**
         *
         * Asserts that actual is empty.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function empty($actual): self
        {
            $this->assertEmpty($actual, _('The result is not empty'));
            return $this;
        }


        /**
         *
         * Asserts that actual is not empty.
         *
         * @param mixed $actual The value to check.
         *
         * @return Unit
         *
         */
        public function def($actual): self
        {
            $this->assertNotEmpty($actual, _('The result is empty'));
            return $this;
        }

        /**
         *
         * Asserts that values contains a value.
         *
         * @param mixed $value The value to check the existence
         * @param iterable $values All values.
         *
         * @return Unit
         *
         */
        public function exist($value, iterable $values): self
        {
            $this->assertContains($value, $values, _('The value has not been found in all values'));
            return $this;
        }

        /**
         *
         * Asserts that a values does not contain a value.
         *
         * @param mixed $value The value to check the existence
         * @param iterable $values All values.
         *
         * @return Unit
         *
         */
        public function notExist($value, iterable $values): self
        {
            $this->assertNotContains($value, $values, _('The value has been found in all values'));
            return $this;
        }

        /**
         *
         * Asserts that an array does not have a specified key.
         *
         * @param mixed $key The key to verify the non existence.
         * @param array $array The array to parse.
         *
         * @return Unit
         *
         */
        public function notHas($key, array $array): self
        {
            $this->assertArrayNotHasKey($key, $array, _('The key has been found in the array'));
            return $this;
        }

        /**
         *
         * Asserts that an array has a specified key.
         *
         * @param mixed $key The key to verify the existence.
         * @param array $array The array to parse.
         *
         * @return Unit
         *
         */
        public function has($key, array $array): self
        {
            $this->assertArrayHasKey($key, $array, _('The key has not been found in the array'));
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
        public function identic($expected, $actual): self
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
        public function different($expected, $actual): self
        {
            $this->assertNotEquals($expected, $actual, _('The actual value is not identic to the expected value'));
            return $this;
        }

        /**
         *
         * Asserts that a haystack contains only values of a given type.
         *
         * @param string $type The type to check.
         * @param iterable $values All values.
         * @param boolean $native The type.
         *
         * @return Unit
         *
         */
        public function only(string $type, iterable $values, bool $native = null): self
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
         * @param string $type The type to check.
         * @param iterable $values All values.
         * @param boolean $native The type.
         *
         * @return Unit
         *
         */
        public function notOnly(string $type, iterable $values, bool $native = null): self
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
         * @param class-string $instance The expected instance.
         * @param mixed $actual The actual instance.
         *
         * @return Unit
         */
        public function is(string $instance, $actual): self
        {
            $this->assertInstanceOf(
                $instance,
                $actual,
                _('The instance is not an instance of the expected class')
            );
            return $this;
        }


        /**
         *
         * Asserts that a variable is not of a given type.
         *
         * @param class-string $instance The expected instance.
         * @param mixed $actual The actual instance.
         *
         * @return Unit
         *
         */
        public function not(string $instance, $actual): self
        {
            $this->assertNotInstanceOf(
                $instance,
                $actual,
                _('The instance is not an instance of the expected class')
            );
            return $this;
        }

        /**
         *
         * Asserts that a class has a specified attribute.
         *
         * @param string $name The attribute name.
         * @param string $class The class name.
         *
         * @return Unit
         *
         */
        public function hasAttribute(string $name, string $class): self
        {
            $this->assertClassHasAttribute($name, $class, _('The class attribute has not been found'));
            return $this;
        }

        /**
         *
         * Asserts that a class does not have a specified attribute.
         *
         * @param string $name The attribute name.
         * @param string $class The class name.
         *
         * @return self
         *
         */
        public function hasNotAttribute(string $name, string $class): self
        {
            $this->assertClassNotHasAttribute($name, $class, _('The class attribute has been found'));
            return $this;
        }

        /**
         *
         * Asserts that a class has a specified static attribute.
         *
         * @param string $name The attribute name.
         * @param string $class The class name.
         *
         * @return Unit
         *
         */
        public function hasStatic(string $name, string $class): self
        {
            $this->assertClassHasStaticAttribute($name, $class, _('The attribute has not been found in the class'));
            return $this;
        }

        /**
         *
         * Asserts that a class does not have a specified static attribute.
         *
         * @param string $name The attribute name.
         * @param string $class The class name.
         *
         * @return Unit
         */
        public function hasNotStatic(string $name, string $class): self
        {
            $this->assertClassNotHasStaticAttribute($name, $class, _('The attribute has been found in the class'));
            return $this;
        }
    }
}
