<?php

declare(strict_types=1);

namespace Eywa\Database\Model {

    use Closure;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Records;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Html\Pagination\Pagination;
    use ReflectionException;
    use stdClass;

    /**
     * Class Model
     * @package Eywa\Database\Model
     */
    abstract class Model
    {

        /**
         *
         * The table name
         *
         */
        protected static string $table = '';

        /**
         *
         * The pagination slug
         *
         */
        protected static string $slug = '';

        /**
         *
         * The table prefix
         *
         */
        protected static string $prefix = '';


        /**
         *
         * The pagination limit
         *
         */
        protected static int $limit = 24;

        /**
         *
         * The columns name of the table
         *
         * @var array
         *
         */
        private static array $columns = [];

        /**
         *
         * The primary ey of the table
         *
         */
        private static string $primary = '';

        /**
         *
         * The html code before the records content
         *
         */
        protected static string $html_before_records_content = '<div>';

        /**
         *
         * The html code before the pagination content
         *
         */
        protected static string $html_before_pagination_content = '<div>';

        /**
         *
         * The html code after the records content
         *
         */
        protected static string $html_after_records_content = '</div>';

        /**
         *
         * The html code after the pagination content
         *
         */
        protected static string $html_after_pagination_content = '</div>';


        /**
         *
         * Callack called before destroy a record
         * if return false the destroy do nothing
         *
         * @param stdClass $record
         *
         * @return bool
         *
         */
        abstract protected static function beforeDestroy(stdClass $record): bool;

        /**
         *
         * Callback called if record has been removed successfully
         *
         * @param stdClass $record
         *
         */
        abstract protected static function afterDestroy(stdClass $record): void;

        /**
         *
         * Callack called before update a record
         * if return false the update do nothing
         *
         * @param stdClass $record
         *
         * @return bool
         *
         */
        abstract protected static function beforeUpdate(stdClass $record): bool;

        /**
         *
         * Callback called if record has been updated successfully
         *
         * @param stdClass $record
         *
         */
        abstract protected static function afterUpdate(stdClass $record): void;


        /**
         *
         * Check the daa before create it
         *
         * @param Collect $origin
         * @param array $modify
         *
         * @return bool
         *
         */
        abstract protected static function beforeCreate(Collect $origin, array &$modify): bool;

        /**
         * @param array $data
         */
        abstract protected static function afterCreate(array $data): void;

        /**
         *
         *
         *
         * @param Closure $closure
         * @param int $page
         *
         * @return string
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function paginate(Closure $closure, int $page): string
        {
            $pagination = sprintf(
                '%s%s%s',
                static::$html_before_pagination_content,
                (new Pagination($page, static::$limit, 100))->render(static::$slug),
                static::$html_after_pagination_content
            );

            $secure = function (stdClass $class) {

                $x = new stdClass();
                foreach (class_to_array($class) as $k => $v) {
                    $x->$k = htmlentities(strval($v), ENT_QUOTES, 'UTF-8');
                }

                return $x;
            };
            $records =  sprintf(
                '%s%s%s',
                static::$html_before_records_content,
                collect(
                    static::sql()->take(
                        static::$limit,
                        (($page) - 1) * static::$limit
                    )->by(static::sql()->primary())->get()
                )->for($secure)->for($closure)->join(''),
                static::$html_after_records_content
            );

            return sprintf(
                '%s%s',
                rtrim($records),
                rtrim($pagination)
            );
        }



        /**
         *
         * Remove a record if exist
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function destroy(int $id): bool
        {
            $record = static::find($id);
            if (static::beforeDestroy($record)) {
                if (static::sql()->where(static::primary(), EQUAL, $id)->delete()) {
                    static::afterDestroy($record);
                    return true;
                }
                return false;
            }

            return false;
        }


        /**
         *
         * Update a record
         *
         * @param int $id
         * @param array $values
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function update(int $id, array $values): bool
        {
            $record = static::find($id);
            if (static::beforeUpdate($record)) {
                if (static::sql()->update($id, $values)) {
                    static::afterUpdate($record);
                    return true;
                }
                return false;
            }
            return  false;
        }

        /**
         *
         * Create a new record
         *
         * @param array $record
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function create(array $record): bool
        {
            $modifiy = [];

            if (static::beforeCreate(collect($record), $modifiy)) {
                if (static::sql()->create($modifiy)) {
                    static::afterCreate($modifiy);
                    return true;
                }
                return false;
            }
            return  false;
        }

        /**
         *
         * Truncate the table
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function truncate()
        {
            return static::sql()->truncate();
        }

        /**
         *
         * Find a record based on it's id
         *
         * @param int $id
         *
         * @return stdClass
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function find(int $id): stdClass
        {
            $x = static::sql()->where(static::primary(), EQUAL, $id)->get();

            is_false(array_key_exists(0, $x), true, 'We have not found the record');

            return collect($x)->get(0);
        }

        /**
         *
         * Get all records different by the passed value
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return array
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function different(string $column, $expected): array
        {
            return static::sql()->where($column, DIFFERENT, $expected)->get();
        }


        /**
         *
         * Get a record by the pased values
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return stdClass
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function by(string $column, $expected): stdClass
        {
            $x = static::sql()->where($column, EQUAL, $expected)->get();

            is_false(array_key_exists(0, $x), true, 'We have not found the record');

            return collect($x)->get(0);
        }

        /**
         *
         * Search a value
         *
         * @param string $value
         *
         * @return array
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function search(string $value): array
        {
            return static::sql()->like($value)->get();
        }

        /**
         *
         * Get all columns in a table
         *
         * @return array
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function columns(): array
        {
            if (not_def(static::$columns)) {
                static::$columns = static::sql()->columns();
            }
            return static::$columns;
        }

        /**
         *
         * Found the primary key
         *
         * @return string
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final public static function primary(): string
        {
            if (not_def(static::$primary)) {
                static::$primary = static::sql()->primary();
            }
            return static::$primary;
        }


        /**
         *
         * Get an instance of the sql with the correct environement
         *
         * @return Records
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        final private static function sql(): Records
        {
            $x = def(static::$prefix) ?  sprintf('%s_%s', static::$prefix, static::$table) : static::$table;

            return (new Sql(ioc(Connect::class)))->from($x);
        }
    }
}
