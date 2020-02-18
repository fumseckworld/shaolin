<?php
declare(strict_types=1);

namespace Eywa\Database\Seed {


    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use Faker\Generator;


    abstract class Seeder
    {

        /**
         *
         * An intance of faker
         *
         */
        protected static Generator $faker;

        /**
         *
         * An instance of table
         *
         */
        protected static Table $table;

        /**
         *
         * An instance of collect
         *
         */
        protected static Collect $set;

        /**
         *
         * The connexion to the base
         *
         */
        protected static Connexion $connexion;

        /**
         *
         * The table name
         *
         */
        public static string $from = '';

        /**
         *
         * The number of record to generate
         *
         */
        public static int $generate = 100;

        /**
         *
         * The seeding title
         *
         */
        public static string $title = '';


        /**
         *
         * The seeding success message
         *
         */
        public static string $success_message = '';

        /**
         *
         * The seeding success message
         *
         */
        public static string $error_message = '';


        /**
         *
         * To generate one record
         *
         * @param Generator $generator
         * @param Table $table
         * @param Seeder $seeder
         *
         * @throws Kedavra
         *
         */
        abstract public function each(Generator $generator,Table $table,Seeder $seeder): void;

        /**
         *
         * Set a column value
         *
         * @param string $column
         * @param $value
         *
         * @return Seeder
         *
         *
         */
        public function set(string $column,$value): Seeder
        {
            static::$set->put($column,static::$connexion->secure($value));

            return $this;
        }

        /**
         *
         * Execute the seeding
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function seed() : bool
        {
            static::$connexion = app()->connexion()->development();

            static::$faker = ioc('faker');

            static::$table = (new Table(static::$connexion));

            $table = static::$from;

            $db = static::$connexion->base();

            is_false(static::$table->exist(static::$from),true,"The $table table not exist in the $db base");

            static::$table = static::$table->from($table);

            static::$set = collect();

            $values = '';

            $columns = static::$table->columns();

            for ($i=0;$i<static::$generate;$i++)
            {
                static::each(static::$faker,static::$table,new static());

                $tmp = collect();

                foreach ($columns as $column)
                    $tmp->set(static::$set->get($column));

                append($values, '('. trim($tmp->join(),', ') . '),');

                static::$set->clear();

                $tmp->clear();
            }

            $x = collect($columns)->join();

            $table = static::$from;

            $sql = trim("INSERT INTO {$table} ($x) VALUES {$values}",', ') ;

            return static::$connexion->set($sql)->execute();

        }

        /**
         *
         * Add the value to the primary key
         *
         * @param string $column
         *
         * @return Seeder
         *
         */
        public function primary(string $column): Seeder
        {
            $value =  in_array(static::$connexion->driver(),[MYSQL, SQLITE]) ? 'NULL' : 'DEFAULT';

            static::$set->put($column,$value);

            return  $this;
        }

    }
}