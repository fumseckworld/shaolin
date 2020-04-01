<?php

declare(strict_types=1);

namespace Eywa\Database\Query {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use Eywa\Html\Pagination\Pagination;
    use PDO;

    class Sql implements Records
    {
        protected const VALID_OPERATORS = [ EQUAL,DIFFERENT,SUPERIOR,INFERIOR,INFERIOR_OR_EQUAL,SUPERIOR_OR_EQUAL  ];

        /**
         *
         * The name of the table
         *
         */
        private string $table;

        /**
         *
         * The connexion to the base
         *
         */
        private Connect $connect;

        /**
         *
         * The limit
         *
         */
        private string $limit = '';

        /**
         *
         * The selected columns
         *
         */
        private string $columns = '*';

        /**
         *
         * The where clause
         *
         */
        private string $where = '';

        /**
         *
         * The order by
         *
         */
        private string $by = '';

        /**
         *
         * The join clause
         *
         */
        private string $join = '';

        /**
         *
         * The union clause
         *
         */
        private string $union = '';

        /**
         *
         * The or clause
         *
         */
        private string $or = '';

        /**
         *
         * The and clause
         *
         */
        private string $and = '';
        /**
         *
         * The pagination
         *
         */
        private string $pagination = '';
        /**
         *
         * The html code
         *
         */
        private string $records = '';
        private string $from;


        /**
         * @inheritDoc
         */
        public function __construct(Connect $connect, string $table)
        {
            $this->table = $table;
            $this->from = sprintf('FROM %s', $table);
            $this->connect = $connect;
        }

        /**
         * @inheritDoc
         */
        public function take(int $limit, int $offset = 0): Sql
        {
            $this->limit = $this->connexion()->mysql()
                ? sprintf(
                    'LIMIT %d,%d',
                    $offset,
                    $limit
                )
                : sprintf(
                    'LIMIT %d OFFSET %d',
                    $limit,
                    $offset
                );

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function by(string $column, string $order = DESC): Sql
        {
            $this->by = sprintf('ORDER BY %s %s', $column, $order);

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function join(
            string $type,
            string $condition,
            string $first_table,
            string $second_table,
            string $first_param,
            string $second_param
        ): Sql {
            not_in(
                [
                    LEFT_JOIN,
                    RIGHT_JOIN,
                    CROSS_JOIN,
                    NATURAL_JOIN,
                    INNER_JOIN,
                    FULL_JOIN
                ],
                $type,
                true,
                'The type is invalid'
            );

            append(
                $this->join,
                sprintf(
                    'SELECT %s FROM %s %s %s ON %s.%s %s %s.%s ',
                    $this->columns,
                    $first_table,
                    $type,
                    $second_table,
                    $first_table,
                    $first_param,
                    $condition,
                    $second_table,
                    $second_param
                )
            );

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function union(
            string $type,
            string $first_table,
            string $second_table,
            string $first_column,
            string $second_column
        ): Sql {
            not_in([UNION,UNION_ALL], $type, true, 'The type is not valid');

            if (not_def($first_column, $second_column)) {
                append(
                    $this->union,
                    sprintf('SELECT * FROM %s %s SELECT * FROM %s ', $first_table, $type, $second_table)
                );
            } else {
                append(
                    $this->union,
                    sprintf(
                        'SELECT %s FROM %s %s SELECT %s FROM %s',
                        $first_column,
                        $first_table,
                        $type,
                        $second_column,
                        $second_table
                    )
                );
            }

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function or(string $column, string $condition, $expected): Sql
        {
            not_in(self::VALID_OPERATORS, $condition, true, 'The used condition operator is not valid');

            if (is_string($expected)) {
                append(
                    $this->or,
                    sprintf(
                        'OR %s %s %s ',
                        $column,
                        html_entity_decode($condition),
                        $this->connexion()->secure($expected)
                    )
                );
            } else {
                append(
                    $this->or,
                    sprintf(
                        'OR %s %s %d ',
                        $column,
                        html_entity_decode($condition),
                        $expected
                    )
                );
            }

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function and(string $column, string $condition, $expected): Sql
        {
            not_in(self::VALID_OPERATORS, $condition, true, 'The used condition operator is not valid');

            if (is_string($expected)) {
                append(
                    $this->and,
                    sprintf(
                        'AND %s %s %s ',
                        $column,
                        html_entity_decode($condition),
                        $this->connexion()->secure($expected)
                    )
                );
            } else {
                append(
                    $this->and,
                    sprintf(
                        'AND %s %s %d ',
                        $column,
                        html_entity_decode($condition),
                        $expected
                    )
                );
            }

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function between(string $column, int $min, int $max): Sql
        {
            $this->where = sprintf('WHERE %s BETWEEN %d AND %d', $column, $min, $max);

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function only(array $columns): Sql
        {
            $this->columns = collect($columns)->join(', ');

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function where(string $column, string $condition, $expected): Sql
        {
            not_in(self::VALID_OPERATORS, $condition, true, 'The used condition operator is not valid');

            $this->where = is_numeric($expected)
                ?
                    sprintf(
                        'WHERE %s %s %d',
                        $column,
                        html_entity_decode($condition),
                        $expected
                    )
                :
                    sprintf(
                        'WHERE %s %s %s',
                        $column,
                        html_entity_decode($condition),
                        $this->connexion()->secure($expected)
                    );

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function exist(): bool
        {
            return def($this->get());
        }

        /**
         * @inheritDoc
         */
        public function sql(): string
        {
            $where = def($this->where) ? $this->where : '';

            $order = def($this->by) ? $this->by : '';

            $limit = def($this->limit) ? $this->limit : '';

            $join = def($this->join) ? $this->join : '';

            $union = def($this->union) ? $this->union : '';

            $or = def($this->or) ? $this->or : '';

            $and = def($this->and) ? $this->and : '';

            $columns = def($this->columns) ? $this->columns : "*";

            if (def($union)) {
                return "$union $where $and $or $order $limit";
            } elseif (def($join)) {
                return "$join $and $or $order $limit";
            }

            return "SELECT $columns {$this->from} $where $and $or $order $limit ";
        }

        /**
         * @inheritDoc
         */
        public function get(): array
        {
            return $this->connexion()->set($this->sql())->get(PDO::FETCH_OBJ);
        }

        /**
         * @inheritDoc
         */
        public function execute(): bool
        {
            return $this->connexion()->set($this->sql())->execute();
        }

        /**
         * @inheritDoc
         */
        public function delete(): bool
        {
            $id = $this->primary();

            $bool = collect();

            foreach ($this->get() as $record) {
                $bool->push(
                    $this->connexion()->set(
                        sprintf(
                            'DELETE FROM %s WHERE %s = %d',
                            $this->table,
                            $id,
                            $record->$id
                        )
                    )->execute()
                );
            }

            return $bool->ok();
        }

        /**
         * @inheritDoc
         */
        public function update(int $id, array $values): bool
        {
            $primary = $this->primary();

            $columns = collect();

            $table = $this->table;

            foreach ($values as $k => $value) {
                if (different($k, $primary)) {
                    $columns->push("$k =" . $this->connexion()->secure($value));
                }
            }

            $columns =  $columns->join(', ');

            $sql = "UPDATE $table SET $columns WHERE $primary = $id";

            return  $this->connexion()->set($sql)->execute();
        }

        /**
         * @inheritDoc
         */
        public function create(array $record): bool
        {
            $x = collect(collect($this->columns())->del([$this->primary()])->all())->join();

            $sql = "INSERT INTO {$this->table} ($x) VALUES( ";

            foreach ($this->columns() as $column) { // modify it
                if (array_key_exists($column, $record)) {
                    append($sql, $this->connexion()->secure($record[$column]), ',');
                }
            }

            $sql = trim($sql, ',');

            append($sql, ')', ',');

            $sql = trim($sql, ',');

            return $this->connexion()->set($sql)->execute();
        }

        /**
         * @inheritDoc
         */
        public function like(string $search): Sql
        {
            if ($this->connexion()->mysql() || $this->connexion()->postgresql()) {
                $columns = collect($this->columns())->join();
                $this->where = "WHERE CONCAT($columns) LIKE '%$search%'";
            } else {
                $fields = collect($this->columns());

                $end = $fields->last();

                $columns = '';

                foreach ($fields->all() as $column) {
                    if (different($column, $end)) {
                        append($columns, "$column LIKE '%$search%' OR ");
                    } else {
                        append($columns, "$column LIKE '%$search%'");
                    }
                }

                $this->where = "WHERE $columns";
            }


            return $this;
        }


        /**
         * @inheritDoc
         */
        public function primary(): string
        {
            switch ($this->connexion()->driver()) {
                case MYSQL:
                    return strval(collect($this->connexion()
                        ->set("show columns from {$this->table} where `Key` = 'PRI';")->get(COLUMNS))->first());
                case POSTGRESQL:
                    return strval(
                        collect($this->connexion()
                        ->set(
                            sprintf(
                                'select column_name 
                                    FROM information_schema.key_column_usage 
                                    WHERE table_name = \'%s\' 
                                    and constraint_name =\'%s_pkey\'',
                                $this->table,
                                $this->table
                            )
                        )->get(PDO::FETCH_COLUMN))->first()
                    );
                case SQLITE:
                    foreach ($this->connexion()->set("PRAGMA table_info({$this->table})")->get(OBJECTS) as $value) {
                        if ($value->pk) {
                            return strval($value->name);
                        }
                    }
                    break;
                case SQL_SERVER:
                    return  strval(
                        collect($this->connexion()
                            ->set(
                                sprintf(
                                    'SELECT COLUMN_NAME, CONSTRAINT_NAME
                                            FROM INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE 
                                            WHERE TABLE_NAME = \'%s\';',
                                    $this->table
                                )
                            )->get(COLUMNS))->first()
                    );
            }
            throw  new Kedavra('We have not found a primary key');
        }

        /**
         * @inheritDoc
         */
        public function columns(): array
        {
            $fields = collect();

            switch ($this->connexion()->driver()) {
                case MYSQL:
                    return $this->connexion()
                        ->set(
                            sprintf(
                                'SHOW FULL COLUMNS FROM %s',
                                $this->table
                            )
                        )->get(COLUMNS);
                case POSTGRESQL:
                    return $this->connexion()
                            ->set(
                                sprintf(
                                    'SELECT column_name 
                                        FROM information_schema.columns 
                                        WHERE table_name =\'%s\'',
                                    $this->table
                                )
                            )->get(COLUMNS);
                case SQLITE:
                    $x = function ($x) {
                        return $x->name ;
                    };
                    return collect($this->connexion()
                        ->set(sprintf(
                            'PRAGMA table_info(%s)',
                            $this->table
                        ))->get(OBJECTS))->for($x)->all();
                default:
                    return $fields->all();
            }
        }

        /**
         * @inheritDoc
         */
        public function paginate(callable $callback, int $current_page, int $limit = 20): Sql
        {
            $this->pagination = (new Pagination($current_page, $limit, $this->sum()))->paginate();

            $this->records =  collect($this->take($limit, (($current_page) - 1) * $limit)->by($this->primary())->get())
                            ->for($callback)->join('');

            return  $this;
        }

        /**
         * @inheritDoc
         */
        public function pagination(): string
        {
            return $this->pagination;
        }


        /**
         * @inheritDoc
         */
        public function records(): string
        {
            return $this->records;
        }

        /**
         * @inheritDoc
         */
        public function connexion(): Connect
        {
            return $this->connect;
        }

        /**
         * @inheritDoc
         */
        public function sum(): int
        {
            return count($this->get());
        }

        /**
         * @inheritDoc
         */
        public function to(int $mode): array
        {
            return $this->connexion()->set($this->sql())->get($mode);
        }
    }
}
