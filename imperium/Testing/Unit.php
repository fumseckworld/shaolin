<?php

namespace Imperium\Testing {


    use PHPUnit\Framework\TestCase;

    use function DI\value;

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
        public function null(...$actual): self
        {
            foreach ($actual as $value) {
                $this->assertNull($value, _('The data are not equal to null'));
            }
            return $this;
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
        public function success(...$conditions): self
        {
            foreach ($conditions as $condition) {
                $this->assertTrue($condition, _('The condition must return true but return false'));
            }
            return $this;
        }

        /**
         * Undocumented function
         *
         * @param class-string<\Throwable> $expected The expected exception.
         * @param string $message The expected message.
         *
         * @return Unit
         *
         */
        public function throw(string $expected, string $message): self
        {
            $this->expectException($expected);
            $this->expectExceptionMessage($message);
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
        public function failure(...$conditions): self
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
        public function boolean(...$values): self
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
        public function notBoolean(...$values): self
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
        public function int(...$values): self
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
        public function notInt(...$values): self
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
        public function num(...$values): self
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
        public function notNum(...$values): self
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
        public function callable(...$values): self
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
        public function notCallable(...$values): self
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
        public function float(...$values): self
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
        public function notFloat(...$values): self
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
        public function iterable(...$values): self
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
        public function notIterable(...$values): self
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
        public function isArray(...$values): self
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
        public function notArray(...$values): self
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
        public function object(...$values): self
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
        public function notObject(...$values): self
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
        public function empty(...$values): self
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
        public function def(...$values): self
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
         * @param iterable $data All values.
         * @param mixed $values all values to check.
         * @return Unit
         *
         */
        public function exist(iterable $data, ...$values): self
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
         * @param iterable $data All values.
         * @param mixed $values The values to check.
         *
         * @return Unit
         *
         */
        public function notExist(iterable $data, ...$values): self
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
         * @param mixed $keys The keys to verify the no existence.
         *
         * @return Unit
         *
         */
        public function notHas(array $array, ...$keys): self
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
         * @param mixed $keys The keys to verify the existence.
         * @return Unit
         *
         */
        public function has(array $array, ...$keys): self
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
         * @param mixed $values The actual instance.
         *
         * @return Unit
         */
        public function is(string $instance, ...$values): self
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
         * @param class-string $instances The expected instance.
         * @param mixed $actual The actual instance.
         *
         * @return Unit
         *
         */
        public function not($actual, ...$instances): self
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
         * @param string $class The class name.
         * @param mixed $attributes the attribute to check
         *
         * @return Unit
         *
         */
        public function hasAttribute(string $class, ...$attributes): self
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
         * @param string $class The class name.
         * @param mixed $attributes The attributes names.
         *
         * @return self
         *
         */
        public function hasNotAttribute(string $class, ...$attributes): self
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
         * @param string $class The class name.
         * @param mixed $attributes The attributes names.
         *
         * @return Unit
         *
         */
        public function hasStatic(string $class, ...$attributes): self
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
         * @param string $class The class name.
         * @param mixed  $attributes the attributes names.
         * @return Unit
         *
         */
        public function hasNotStatic(string $class, ...$attributes): self
        {
            foreach ($attributes as $attribute) {
                $this->assertClassNotHasStaticAttribute($attribute, $class, _('The attribute has been found'));
            }
            return $this;
        }
    }
}
