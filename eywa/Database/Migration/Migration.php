<?php


namespace Eywa\Database\Migration {

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
    abstract class Migration implements Evolution
    {

        /**
         *
         * The creation time of the migration
         *
         */
        public static string $created_at = '';

        /**
         *
         * The table name
         *
         */
        public static string $table = '';

        /**
         *
         * The migration success message
         *
         */
        public static string $up_success_message = '';

        /**
         *
         * The migration error message
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
         * The rollback error message
         *
         */
        public static string $down_error_message = '';

        /**
         *
         * The migration title
         *
         */
        public static string $up_title = '';

        /**
         *
         * The rollback title
         *
         */
        public static string $down_title = '';

        /**
         *
         * All columns to manage
         *
         */
        private static Collect $columns;

        /**
         *
         * All foreign keys to manage
         *
         */
        private static Collect $foreign;

        /**
         *
         * The dev or prod environment
         *
         */
        private static string $env;

        /**
         *
         * The down or up mode
         *
         */
        private static string $mode;

        /**
         *
         * The instance to manage all tables
         *
         */
        private Table $db;

        /**
         *
         * The migration up code
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        abstract public function up(): bool;

        /**
         *
         * The migration down code
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        abstract public function down(): bool;

        /**
         * @inheritDoc
         */
        public function __construct(string $mode, string $env)
        {
            static::$columns = collect();
            static::$foreign = collect();
            static::$env   = $env;
            static::$mode  = $mode;
        }

        /**
         * @inheritDoc
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


                append($sql,"$column $type ");


                if ($size !== 0)
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

            return $this->connexion()->set($sql)->execute();
        }

        /**
         * @inheritDoc
         */
        public function drop(): bool
        {
            return $this->db->drop();
        }

        /**
         * @inheritDoc
         */
        public function truncate(): bool
        {
            return $this->db->truncate();
        }

        /**
         * @inheritDoc
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


                append($column_type,"$column $type");

                $x = " $column_type";

                if ($size !== 0)
                    append($x,"($size)");


                append($sql,$x);
            }

            return  $this->connexion()->set("ALTER TABLE $table ADD COLUMN $sql;")->execute();
        }

        /**
         * @inheritDoc
         */
        public function remove(array $columns): bool
        {
            return $this->db->remove($columns);
        }

        /**
         * @inheritDoc
         */
        public function rename(string $new_name): bool
        {
            return $this->db->rename($new_name);
        }

        /**
         * @inheritDoc
         */
        public function refresh(string $colunn, string $new_column_name): bool
        {
           return $this->db->rename_column($colunn,$new_column_name);
        }

        /**
         * @inheritDoc
         */
        public function remove_foreign(array $columns): bool
        {
            $table = static::$table;

            $x = collect();
            foreach ($columns as $column)
            {
                switch ($this->connexion()->driver())
                {
                    case MYSQL:
                        $x->push($this->connexion()->set(sprintf('ALTER TABLE %s DROP FOREIGN KEY %s',$table,$column))->execute());
                    break;
                    case POSTGRESQL:
                        $x->push($this->connexion()->set(sprintf('ALTER TABLE %s DROP CONSTRAINT %s',$table,$column))->execute());
                    break;
                    default:
                        return false;
                }
            }
            return $x->ok();

        }

        /**
         * @inheritDoc
         */
        public function add(string $column, string $type, int $size = 0, array $constraints = []): Evolution
        {
            static::$columns->push(compact('column', 'type', 'size','constraints'));

            return  $this;
        }

        /**
         * @inheritDoc
         */
        public function foreign(string $column, string $reference, string $reference_column, string $on = '', string $do = ''): Evolution
        {
            $constraint = " FOREIGN KEY ($column) REFERENCES $reference($reference_column)";

            if (def($on,$do))
                append($constraint," $on $do");

            static::$foreign->push(compact('column', 'constraint'));

            return $this;
        }

        /**
         * @return Connect
         *
         * @throws Kedavra
         *
         */
        private function connexion(): Connect
        {
            return static::$env == 'dev' ? development() : production();
        }
    }
}