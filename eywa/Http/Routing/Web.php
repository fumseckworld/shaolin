<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {


    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;

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

        /**
         * @param $search
         * @param int $pdo_mode
         * @return array
         * @throws Kedavra
         */
        public static function like($search,int $pdo_mode = \PDO::FETCH_OBJ)
        {
            return static::sql()->like($search)->execute($pdo_mode);
        }


        /**
         * @inheritDoc
         */
        public function before_validation(Request $request): bool
        {
            // TODO: Implement before_validation() method.
        }

        /**
         * @inheritDoc
         */
        public function after_validation(Request $request): bool
        {
            // TODO: Implement after_validation() method.
        }

        /**
         * @inheritDoc
         */
        public function before_save(Request $request): bool
        {
            // TODO: Implement before_save() method.
        }

        /**
         * @inheritDoc
         */
        public function after_save(Request $request): bool
        {
            // TODO: Implement after_save() method.
        }

        /**
         * @inheritDoc
         */
        public function after_commit(Request $request): bool
        {
            // TODO: Implement after_commit() method.
        }

        /**
         * @inheritDoc
         */
        public function after_rollback(Request $request): bool
        {
            // TODO: Implement after_rollback() method.
        }

        /**
         * @inheritDoc
         */
        public function before_update(Request $request): bool
        {
            // TODO: Implement before_update() method.
        }

        /**
         * @inheritDoc
         */
        public function after_update(Request $request): bool
        {
            // TODO: Implement after_update() method.
        }

        /**
         * @inheritDoc
         */
        public function before_create(Request $request): bool
        {
            // TODO: Implement before_create() method.
        }

        /**
         * @inheritDoc
         */
        public function after_create(Request $request): bool
        {
            // TODO: Implement after_create() method.
        }

        /**
         * @inheritDoc
         */
        public function before_destroy(Request $request): bool
        {
            // TODO: Implement before_destroy() method.
        }

        /**
         * @inheritDoc
         */
        public function after_destroy(Request $request): bool
        {
            // TODO: Implement after_destroy() method.
        }
    }
}