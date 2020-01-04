<?php


namespace Eywa\Http\Routing {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;

    class Task extends Model
    {
        protected static string $table  = "routes";

        protected static bool $task = true;


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
            return static::connection()->execute(static::$create_route_table_query);
        }
    }
}