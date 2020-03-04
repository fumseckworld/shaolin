<?php


namespace Eywa\Database\Migration {

    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
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
         * @var array<string>
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
         * @var Connect
         */
        private static Connect $connect;

        /**
         * @var string
         */
        private static string $mode;
        /**
         * @var string
         */
        private static string $env;


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
         * @param Connect $connect
         * @param string $mode
         * @param string $env
         */
        public function __construct(Connect $connect,string $mode,string $env)
        {
            static::$columns = collect();
            static::$foreign = collect();
            static::$connect = $connect;
            static::$env   = $env;
            static::$mode  = $mode;
        }

        /**
         *
         * Add a new column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param array<int,string> $constraints
         *
         * @return Migration
         *
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

            if (def($on,$do))
                append($constraint," ON $on $do");

            static::$foreign->push(compact('column', 'constraint'));

            return $this;
        }

        /**
         * @param string $name
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function drop_foreign_key(string $name): bool
        {
            $table = static::$table;

            switch ($this->driver())
            {
                case MYSQL:
                    return  $this->connexion()->set(sprintf('ALTER TABLE %s DROP FOREIGN KEY %s',$table,$name))->execute();

                case POSTGRESQL:
                    return  $this->connexion()->set(sprintf('ALTER TABLE %s DROP CONSTRAINT %s',$table,$name))->execute();
                default:
                    return false;
            }
        }

        /**
         *
         * Rename the table
         *
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function rename(string $new_name):bool
        {
            $table = static::$table;
            switch ($this->driver())
            {
                case MYSQL:
                    return  $this->connexion()->set(sprintf('RENAME TABLE %s TO %s',$table,$new_name))->execute();
                case POSTGRESQL:
                    return  $this->connexion()->set(sprintf('ALTER TABLE %s RENAME TO %s',$table,$new_name))->execute();
                default:
                    return false;
            }
        }

        /**
         *
         * Rename the table
         *
         * @param string $column_name
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function rename_column(string $column_name,string $new_name): bool
        {
            $table = static::$table;
            switch ($this->driver())
            {
                case MYSQL:
                case POSTGRESQL:
                    return  $this->connexion()->set(sprintf('ALTER TABLE %s RENAME COLUMN %s TO %s',$table,$column_name,$new_name))->execute();
                default:
                    return false;
            }
        }

        /**
         *
         * Add a foreign key
         *
         * @param string $name
         * @param string $reference
         * @param string $column
         * @param string $on
         * @param string $do
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function add_foreign_key(string $name,string $reference,string $column,string $on,string $do): bool
        {
            $table = static::$table;
            switch ($this->driver())
            {
                case MYSQL:
                case POSTGRESQL:
                    return  $this->connexion()->set(sprintf('ALTER TABLE %s ADD FOREIGN KEY (%s) REFERENCES %s(%s)  ON %s  %s;',$table,$name,$reference,$column,$on,$do))->execute();
                default:
                    return false;
            }

        }

        /**
         * @param string $column
         *
         * @return Migration
         *
         * @throws Kedavra
         *
         */
        private function primary(string $column): Migration
        {
            static::$current_column = $column;
            $size = 0;
            switch ($this->driver())
            {
                case MYSQL:
                    $type = 'INT';
                    $constraints = ['PRIMARY KEY NOT NULL AUTO_INCREMENT'];
                    static::$columns->push(compact('column', 'type','size','constraints'));
                break;
                case POSTGRESQL:
                    $type = 'SERIAL';
                    $constraints =  ['PRIMARY KEY'];
                    static::$columns->push(compact('column', 'type','size','constraints'));
                break;
                case SQLITE:
                    $type = 'INTEGER';
                    $constraints = ['PRIMARY KEY AUTOINCREMENT'];
                    static::$columns->push(compact('column', 'type','size','constraints'));
                break;
            }

            return $this;
        }


        /***
         *
         * Create the table
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function create(): bool
        {
            $table = static::$table;

            $sql = "CREATE TABLE IF NOT EXISTS $table  (";


            foreach (static::$columns->all() as $column)
            {
                $x = collect($column);

                $column = $x->get('column');

                $constraint = collect($x->get('constraints'))->join(' ');

                $type = $x->get('type');

                $size = $x->get('size') ?? 0;

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
                        append($sql,"$column $type ");
                    break;
                }

                if ($size != 0)
                    append($sql," ($size) ");

                append($sql," $constraint , ");

            }

            foreach (static::$foreign->all() as $foreign)
            {
                $x = collect($foreign);

                $constraint = $x->get('constraint');

                append($sql," $constraint, ");
            }

            $sql = trim($sql,', ');

            append($sql, ')');

            static::$foreign->clear();

            static::$columns->clear();

            return static::connexion()->set($sql)->execute();
        }


        /**
         *
         * Update the table
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function update(): bool
        {
            $table = static::$table;




            $sql = '';
            foreach (static::$columns->all() as $column)
            {

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


                append($sql,$x);
            }

              return  static::connexion()->set("ALTER TABLE $table ADD COLUMN $sql;")->execute();



        }

        /**
         *
         * Remove columns
         *
         * @param string ...$columns
         *
         * @return bool
         *
         * @throws Kedavra
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
                $result->push($connexion->set("ALTER TABLE $table DROP $x")->execute());
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
         * @throws Kedavra
         */
        public function drop(string $table): bool
        {
            return equal(static::$env,'dev') ? (new Table(development()))->from($table)->drop() : (new Table(production()))->from($table)->drop() ;
        }

        /**
         *
         * @return array<string>
         *
         * @throws Kedavra
         *
         */
        public function columns():array
        {
            return static::$env === 'dev' ? (new Sql(development(),static::$table))->columns() : (new Sql(production(),static::$table))->columns();
        }

        /**
         * @return string
         * @throws Kedavra
         */
        private function datetime()
        {
            switch ($this->driver())
            {
                case MYSQL:
                    return 'DATETIME';
                case POSTGRESQL:
                    return 'TIMESTAMP';
                case SQLITE:
                    return 'TEXT';
                default:
                    return '';
            }
        }

        /**
         * @return string
         *
         * @throws Kedavra
         */
        private function text()
        {
            switch ($this->driver())
            {
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

        /**
         * @return string
         * @throws Kedavra
         */
        private function long_text()
        {
            switch ($this->driver())
            {
                case MYSQL:
                    return 'LONGTEXT';
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
         */
        private function connexion(): Connect
        {
           return static::$env == 'dev' ? development() : production();
        }

        /**
         *
         *
         * @return string
         *
         * @throws Kedavra
         */
        private function driver(): string
        {
            return $this->connexion()->driver();
        }

    }
}