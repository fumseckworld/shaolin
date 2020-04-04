<?php

declare(strict_types=1);

namespace Eywa\Database\Model {

    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use stdClass;

    class Model implements Interact
    {
        private string $table;


        private int $limit;

        /**
         * Model constructor.
         *
         * @param string $table
         * @param int $limit
         *
         */
        public function __construct(string $table, int $limit = 20)
        {
            $this->table = $table;
            $this->limit = $limit;
        }

        /**
         * @inheritDoc
         */
        public function all(): array
        {
            return (new Sql(ioc(Connect::class), $this->table))->get();
        }
        /**
         * @inheritDoc
         */
        public function destroy(int $id): bool
        {
            return (new Sql(ioc(Connect::class), $this->table))->where(self::primary(), EQUAL, $id)->delete();
        }


        /**
         * @inheritDoc
         */
        public function update(int $id, array $values): bool
        {
            return (new Sql(ioc(Connect::class), $this->table))->update($id, $values);
        }

        /**
         * @inheritDoc
         */
        public function create(array $record): bool
        {
            return (new Sql(ioc(Connect::class), $this->table))->create($record);
        }

        /**
         * @inheritDoc
         */
        public function find(int $id): stdClass
        {
            $x = (new Sql(ioc(Connect::class), $this->table))->where(self::primary(), EQUAL, $id)->get();

            is_false(array_key_exists(0, $x), true, 'We have not found the record');

            return collect($x)->get(0);
        }

        /**
         * @inheritDoc
         */
        public function different(string $column, $expected): array
        {
            return (new Sql(ioc(Connect::class), $this->table))->where($column, DIFFERENT, $expected)->get();
        }

        /**
         * @inheritDoc
         */
        public function by(string $column, $expected): stdClass
        {
            $x = (new Sql(ioc(Connect::class), $this->table))->where($column, EQUAL, $expected)->get();

            is_false(array_key_exists(0, $x), true, 'We have not found the record');

            return collect($x)->get(0);
        }

        /**
         * @inheritDoc
         */
        public function search(string $value): array
        {
            return (new Sql(ioc(Connect::class), $this->table))->like($value)->get();
        }

        /**
         * @inheritDoc
         */
        public function columns(): array
        {
            return (new Sql(ioc(Connect::class), $this->table))->columns();
        }

        /**
         * @inheritDoc
         */
        public function primary(): string
        {
            return (new Sql(ioc(Connect::class), $this->table))->primary();
        }

        /**
         * @inheritDoc
         */
        public function paginate(callable $callback, int $current_page): Sql
        {
            return (new Sql(ioc(Connect::class), $this->table))->paginate($callback, $current_page, $this->limit);
        }
    }
}
