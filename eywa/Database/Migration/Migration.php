<?php


namespace Eywa\Database\Migration {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;


    /**
     * Class Migration
     *
     * @package Eywa\Database\Migration
     *
     */
    abstract class Migration
    {

        /**
         *
         * The current table to manage
         *
         */
        public static string $table = '';

        /**
         *
         * The generated migration date
         *
         */
        public static string $created_at = '';

        /**
         *
         * The migrate success message
         *
         */
        public static string $up_success_message = '';

        /**
         *
         * The title used to explain the migration command
         *
         */
        public static string $up_title = '';

        /**
         *
         * The title used to explain the rollback command
         *
         */
        public static string $down_title = '';

        /**
         *
         * The migrate error message
         *
         */
        public static string $up_error_message = '';

        /**
         *
         * The rollback success message
         *
         */
        public static string $down_success_message = '';

        /**
         *
         * The rolback fail message
         *
         */
        public static string $down_error_message = '';

        /**
         *
         * All constrait for a column
         *
         */
        protected static array $constraint = [];

        /**
         *
         * Used to store the current column to save correct constraints
         *
         */
        protected static string $current_column ='';

        /**
         *
         * All added columns
         *
         */
        protected static Collect $columns;

        /**
         * @var Collect
         */
        private static Collect $foreign;

        /**
         *
         * The method to updagrade table
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        abstract public function up(): bool;

        /**
         *
         * The method to reset change
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        abstract public function down(): bool;



        /**
         * Migration constructor.
         *
         * @throws Exception
         *
         */
        public function __construct()
        {
            static::$columns = collect();
            static::$foreign = collect();
        }

        /**
         *
         * Add a new column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param string ...$constraints
         *
         * @return Migration
         *
         * @throws Kedavra
         */
        public function add(string $column,string $type,int $size = 0,string ...$constraints): Migration
        {
            if (equal($type, 'primary'))
                return $this->primary($column);

            static::$current_column = $column;

            static::$columns->push(compact('column', 'type', 'size','constraints'));

            return  $this;
        }

        /**
         * @param string $column
         * @param string $table
         * @param string $table_column
         * @param string $on
         * @param string $do
         *
         * @return Migration
         *
         */
        public function foreign(string $column, string $table, string $table_column, string $on = '', string $do =''): Migration
        {
            $constraint = " FOREIGN KEY ($column) REFERENCES $table($table_column)";

            static::$foreign->push(compact('column', 'constraint'));

            return $this;
        }

        /**
         * @param string ...$names
         * @return bool
         */
        public function drop_foreign(string ...$names): bool
        {

        }

        /**
         * @param string $column
         *
         * @return Migration
         *
         * @throws Kedavra
         */
        private function primary(string $column): Migration
        {
            static::$current_column = $column;

            switch ($this->driver())
            {
                case MYSQL:
                    $constraint = 'PRIMARY KEY NOT NULL AUTO_INCREMENT';
                break;
                case POSTGRESQL:
                    $constraint = 'SERIAL PRIMARY KEY';
                break;
                case SQLITE:
                    $constraint = 'PRIMARY KEY AUTOINCREMENT';
                break;
            }

            $type = config('migrations','primary_key_type');

            static::$columns->push(compact('column', 'type', 'constraint'));


            return $this;
        }


        /***
         *
         * Create the table
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function create(): bool
        {
            $table = static::$table;
            $sql = "CREATE TABLE IF NOT EXISTS $table  (";

            foreach (static::$columns->all() as $column)
            {
                $x = collect($column);

                $constraint = $x->get('constraint');

                $column = $x->get('column');

                $type = $x->get('type');


                $size = $x->get('size');

                switch ($type)
                {
                    case 'string':
                        append($sql, "$column {$this->text()} ");
                    break;
                    case 'longtext':
                        append($sql, "$column {$this->long_text()} ");
                    break;
                    case 'datetime':
                        append($sql, "$column {$this->datetime()} ");
                    break;
                    default:
                        append($sql,$column, " $type ");
                    break;
                }


                if ($size != 0)
                    append($sql," ($size) ");

                append($sql,$constraint);
                append($sql,', ');

            }

            static::$columns->clear();

            foreach (static::$foreign->all() as $foreign)
            {
                $x = collect($foreign);

                $constraint = $x->get('constraint');

                append($sql," $constraint, ");
            }

            static::$foreign->clear();

            $sql = trim($sql,', ');
            append($sql, ')');

            return $this->connexion()->set($sql)->execute();
        }



        /**
         *
         * Update the table
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function update(): bool
        {
            $table = static::$table;

            $result = collect();

            foreach (static::$columns->all() as $column)
            {

                if ((new Table($this->connexion()))->from($table)->has($column['column']))
                    return false;

                $type  = $column['type'];

                $size = $column['size'];

                $column = $column['column'];


                $column_type = '';

                switch ($type)
                {
                    case 'string':
                        append($column_type, "$column {$this->text()} ");
                    break;
                    case 'longtext':
                        append($column_type, "$column {$this->long_text()} ");
                    break;
                    case 'datetime':
                        append($column_type, "$column {$this->datetime()} ");
                    break;
                    default:
                        append($column_type,$column, " $type ");
                    break;
                }

                $x = " $column_type";

                if ($size !== 0)
                    append($x,"($size)");

                $result->push($this->connexion()->set("ALTER TABLE $table ADD COLUMN $x")->execute());
            }

            static::$columns->clear();



            return  $result->ok();
        }

        /**
         *
         * Remove columns
         *
         * @param string ...$columns
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function drop_columns(string ...$columns): bool
        {
            if ($this->columns() === $columns)
              return $this->drop(static::$table);

            $connexion = $this->connexion();
            $result = collect();
            $table = static::$table;
            foreach (static::$columns->all() as $column)
            {
                $x = $column['column'];
                $result->push($connexion->set("ALTER TABLE $table DROP  $x")->execute());
            }

            static::$columns->clear();

            return $result->ok();
        }

        /**
         *
         * Remove the table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function drop(string $table): bool
        {
            return (new Table($this->connexion()))->from($table)->drop();
        }

        /**
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function columns()
        {
            return sql(static::$table)->columns();
        }

        /**
         * @return string
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function datetime()
        {
            switch ($this->driver())
            {
                case MYSQL:
                    return 'DATETIME';
                break;
                case POSTGRESQL:
                    return 'TIMESTAMP';
                break;
                case SQLITE:
                    return 'TEXT';
                break;
                default:
                    return '';
                break;
            }
        }

        /**
         * @return string
         */
        private function text()
        {
            return 'VARCHAR';
        }

        /**
         * @return string
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function long_text()
        {
            switch ($this->driver())
            {
                case MYSQL:
                    return 'LONGTEXT';
                break;
                case POSTGRESQL:
                case SQLITE:
                    return 'TEXT';
                break;
                default:
                    return '';
                break;
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
            $prod = new Connect(env('DB_DRIVER','mysql'),env('DB_NAME','eywa'),env('DB_USERNAME','eywa'),env('DB_PASSWORD','eywa'),intval(env('DB_PORT',3306)),config('connection','options'),env('DB_HOST','localhost'));

            return  equal(config('mode','connexion'),'prod') ? $prod : $prod->development();
        }

        /**
         * @return string
         *
         * @throws Kedavra
         *
         *
         */
        private function driver(): string
        {
            return $this->connexion()->driver();
        }

    }
}