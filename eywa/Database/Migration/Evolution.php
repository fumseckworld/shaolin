<?php

declare(strict_types=1);

namespace Eywa\Database\Migration {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;

    class Evolution
    {
        private string $from;

        private Collect $columns;

        private Collect $foreign;

        private Table $table;

        private string $env;

        /**
         *
         * Evolution constructor.
         *
         * @param string $env
         * @param string $table
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $env, string $table)
        {
            not_in(['dev','prod','test'], $env, true, 'The mode must be dev, prod or test');

            is_true(not_def($table), true, 'The table name is required');

            $this->env = $env;

            $this->columns  = collect();

            $this->foreign  = collect();

            $this->from = $table;

            $this->table = (new Table($this->connexion()))->from($table);
        }

        /**
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function create(): bool
        {
            $sql = sprintf('CREATE TABLE IF NOT EXISTS %s (', $this->from);

            foreach ($this->columns->all() as $column) {
                $x = collect($column);

                $column = $x->get('column');

                $constraint = collect($x->get('constraints'))->join(' ');

                $type = $x->get('type');

                $size = $x->get('size') ?? 0;
                switch ($type) {
                    case 'string':
                        append($sql, "$column {$this->text()} ");
                        break;
                    case 'longtext':
                        append($sql, "$column {$this->longtext()} ");
                        break;
                    case 'datetime':
                        append($sql, "$column {$this->datetime()} ");
                        break;
                    default:
                        append($sql, "$column $type ");
                        break;
                }

                if ($size !== 0) {
                    append($sql, " ($size) ");
                }

                append($sql, " $constraint , ");
            }

            foreach ($this->foreign->all() as $foreign) {
                $x = collect($foreign);

                $constraint = $x->get('constraint');

                append($sql, " $constraint, ");
            }

            $sql = trim($sql, ', ');

            append($sql, ')');

            $this->foreign->clear();

            $this->columns->clear();

            return $this->connexion()->set($sql)->execute();
        }
        /**
         *
         * Drop the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function drop(): bool
        {
            return $this->table->drop();
        }

        /**
         *
         * Truncate the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function truncate(): bool
        {
            return $this->table->truncate();
        }

        /**
         *
         * Update a table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function update(): bool
        {
            $sql = '';

            foreach ($this->columns->all() as $column) {
                $type  = collect($column)->get('type');

                $size = collect($column)->get('size');

                $column = collect($column)->get('column');


                $column_type = '';


                append($column_type, "$column $type");

                $x = " $column_type";

                if ($size !== 0) {
                    append($x, "($size)");
                }

                append($sql, $x);

                return $this->connexion()->set(sprintf('ALTER TABLE %s ADD COLUMN %s;', $this->from, $sql))->execute();
            }

            return false;
        }

        /**
         *
         * Remove all columns
         *
         * @param array<string> $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function remove(array $columns): bool
        {
            return $this->table->remove($columns);
        }

        /**
         *
         * Rename a table
         *
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function rename(string $new_name): bool
        {
            return $this->table->rename($new_name);
        }

        /**
         *
         * Rename a column
         *
         * @param string $column
         * @param string $new_column_name
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function refresh(string $column, string $new_column_name): bool
        {
            return $this->table->renameColumn($column, $new_column_name);
        }

        /**
         *
         * Remove foreign key columns
         *
         * @param array $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function removeForeign(array $columns): bool
        {
            $x = collect();
            foreach ($columns as $column) {
                switch ($this->connexion()->driver()) {
                    case MYSQL:
                        $x->push(
                            $this->connexion()->set(
                                sprintf(
                                    'ALTER TABLE %s DROP FOREIGN KEY %s',
                                    $this->from,
                                    $column
                                )
                            )
                            ->execute()
                        );
                        break;
                    case POSTGRESQL:
                        $x->push($this->connexion()->set(
                            sprintf(
                                'ALTER TABLE %s DROP CONSTRAINT %s',
                                $this->from,
                                $column
                            )
                        )->execute());
                        break;
                    default:
                        return false;
                }
            }
            return $x->ok();
        }

        /**
         *
         * Add a column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param array<string> $constraints
         *
         * @return Evolution
         *
         * @throws Kedavra
         *
         */
        public function add(string $column, string $type, int $size = 0, array $constraints = []): Evolution
        {
            if (equal($type, 'primary')) {
                return $this->primary($column);
            }

            $this->columns->push(compact('column', 'type', 'size', 'constraints'));

            return  $this;
        }


        /**
         *
         * Add a new foreign key
         *
         * @param string $column
         * @param string $reference
         * @param string $reference_column
         * @param string $on
         * @param string $do
         *
         * @return Evolution
         *
         */
        public function foreign(
            string $column,
            string $reference,
            string $reference_column,
            string $on = '',
            string $do = ''
        ): Evolution {
            $constraint = " FOREIGN KEY ($column) REFERENCES $reference($reference_column)";

            if (def($on, $do)) {
                append($constraint, sprintf(' %s %s ', $on, $do));
            }

            $this->foreign->push(compact('column', 'constraint'));

            return $this;
        }

        /**
         * @param string $column
         * @return Evolution
         *
         * @throws Kedavra
         *
         */
        private function primary(string $column): Evolution
        {
            $size = 0;
            switch ($this->connexion()->driver()) {
                case MYSQL:
                    $type = 'INT';
                    $constraints = ['PRIMARY KEY NOT NULL AUTO_INCREMENT'];
                    $this->columns->push(compact('column', 'type', 'size', 'constraints'));
                    break;
                case POSTGRESQL:
                    $type = 'SERIAL';
                    $constraints =  ['PRIMARY KEY'];
                    $this->columns->push(compact('column', 'type', 'size', 'constraints'));
                    break;
                case SQLITE:
                    $type = 'INTEGER';
                    $constraints = ['PRIMARY KEY AUTOINCREMENT'];
                    $this->columns->push(compact('column', 'type', 'size', 'constraints'));
                    break;
            }

            return $this;
        }

        /**
         *
         * @throws Kedavra
         *
         * @return string
         *
         */
        private function longtext(): string
        {
            switch ($this->connexion()->driver()) {
                case MYSQL:
                    return  'LONGTEXT';
                case POSTGRESQL:
                case SQLITE:
                    return 'TEXT';
                default:
                    return '';
            }
        }

        /**
         * @return Connect
         *
         * @throws Kedavra
         *
         */
        private function connexion(): Connect
        {
            if ($this->env == 'dev') {
                return development();
            } elseif ($this->env == 'prod') {
                return production();
            }

            return tests();
        }

        /**
         * @return string
         * @throws Kedavra
         */
        private function datetime(): string
        {
            switch ($this->connexion()->driver()) {
                case MYSQL:
                case SQLITE:
                    return 'DATETIME';
                case POSTGRESQL:
                    return 'TIMESTAMP';
                default:
                    return '';
            }
        }
        /**
         *
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        private function text(): string
        {
            switch ($this->connexion()->driver()) {
                case MYSQL:
                    return  'VARCHAR';
                case POSTGRESQL:
                    return 'character varying';
                case SQLITE:
                    return  'text';
                default:
                    return  '';
            }
        }
    }
}
