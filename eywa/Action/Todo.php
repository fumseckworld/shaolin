<?php


namespace Eywa\Action {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;

    class Todo extends Model
    {

        protected static string $table = 'todo';

        protected static bool $todo = true;


        /**
         *
         * Create the base
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public static function generate(): bool
        {
            return static::connection()->execute(static::$create_todo);
        }
    }
}