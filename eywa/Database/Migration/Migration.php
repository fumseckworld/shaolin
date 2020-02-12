<?php


namespace Eywa\Database\Migration {


    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;


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
        public static string $generared_at = '';

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
         * The connexion to the base
         *
         */
        protected static Connexion $connexion;

        /**
         *
         * All added columns
         *
         */
        protected static Collect $columns;

        /**
         *
         * The method to updagrade table
         *
         * @return bool
         *
         */
        abstract public function up(): bool;

        /**
         *
         * The method to reset change
         *
         * @return bool
         *
         */
        abstract public function down(): bool;



        /**
         * Migration constructor.
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function __construct()
        {
            static::$columns = collect();
            static::$connexion = app()->connexion();
        }

        /**
         *
         * Add a new column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         *
         * @return Migration
         *
         */
        public function add(string $column,string $type,int $size = 0): Migration
        {
            static::$current_column = $column;

            static::$columns->push(compact('column', 'type', 'size'));

            return  $this;
        }

        /**
         *
         * Remove columns
         *
         * @param string ...$columns
         *
         * @return bool
         *
         */
        public function remove(string ...$columns): bool
        {
            $remove = collect();
            foreach ($columns as $column)
            {
                $remove->push($column);
            }
            return $remove->ok();
        }

        /**
         *
         *
         *
         *
         * @param string $table
         * @return bool
         * @throws Kedavra
         */
        public function drop(string $table): bool
        {
            return (new Table(static::$connexion))->from($table)->drop();
        }

        /**
         *
         * Add column constraint
         *
         * @param string ...$constraints
         *
         * @return Migration
         */
        public function constraint(string ...$constraints): Migration
        {
            static::$constraint[static::$current_column] = collect($constraints)->join(' ');

            return  $this;
        }

        public function check()
        {
            d(static::$columns,static::$constraint,static::$connexion);
        }

    }
}