<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {


    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;

    class Web extends Model
    {
        protected static string $table  = "routes";

        protected static bool $web = true;


        /**
         *
         * Create the base
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public static function generate(): bool
        {
            return static::connection()->set(static::$create_route_table_query)->execute();
        }


    }
}