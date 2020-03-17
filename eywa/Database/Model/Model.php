<?php

declare(strict_types=1);

namespace Eywa\Database\Model {

    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use stdClass;

    abstract class Model implements Interact
    {
        protected static string $table = '';


        protected static int $limit = 20;


        /**
         * @inheritDoc
         */
        public static function destroy(int $id): bool
        {
            return (new Sql(ioc(Connect::class), static::$table))->where(self::primary(), EQUAL, $id)->delete();
        }

        /**
         * @inheritDoc
         */
        public static function update(int $id, array $values): bool
        {
            return (new Sql(ioc(Connect::class), static::$table))->update($id, $values);
        }

        /**
         * @inheritDoc
         */
        public static function create(array $record): bool
        {
            return (new Sql(ioc(Connect::class), static::$table))->create($record);
        }

        /**
         * @inheritDoc
         */
        public static function find(int $id): stdClass
        {
            $x = (new Sql(ioc(Connect::class), static::$table))->where(self::primary(), EQUAL, $id)->get();

            is_false(array_key_exists(0, $x), true, 'We have not found the record');

            return collect($x)->get(0);
        }

        /**
         * @inheritDoc
         */
        public static function different(string $column, $expected): array
        {
            return (new Sql(ioc(Connect::class), static::$table))->where($column, DIFFERENT, $expected)->get();
        }

        /**
         * @inheritDoc
         */
        public static function by(string $column, $expected): stdClass
        {
            $x = (new Sql(ioc(Connect::class), static::$table))->where($column, EQUAL, $expected)->get();

            is_false(array_key_exists(0, $x), true, 'We have not found the record');

            return collect($x)->get(0);
        }

        /**
         * @inheritDoc
         */
        public static function search(string $value): array
        {
            return (new Sql(ioc(Connect::class), static::$table))->like($value)->get();
        }

        /**
         * @inheritDoc
         */
        public static function columns(): array
        {
            return (new Sql(ioc(Connect::class), static::$table))->columns();
        }

        /**
         * @inheritDoc
         */
        public static function primary(): string
        {
            return (new Sql(ioc(Connect::class), static::$table))->primary();
        }

        /**
         * @inheritDoc
         */
        public static function paginate(callable $callback, int $current_page): Sql
        {
            return (new Sql(ioc(Connect::class), static::$table))->paginate($callback, $current_page, static::$limit);
        }
    }
}
