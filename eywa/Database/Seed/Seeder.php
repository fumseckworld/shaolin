<?php
declare(strict_types=1);

namespace Eywa\Database\Seed {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
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
        protected static string $from = '';

        /**
         *
         * The number of record to generate
         *
         */
        protected static int $generate = 100;


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
         * @throws Kedavra
         *
         */
        public function set(string $column,$value): Seeder
        {
            equal($column,static::$table->primary()) ? static::$set->put($column,$value) :   static::$set->put($column,static::$connexion->secure($value));

            return $this;
        }

        /**
         *
         * Execute the seeding
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         * @throws Exception
         */
        public function seed() : bool
        {
            static::$connexion = new Connect(env('DEVELOP_DB_DRIVER'),env('DEVELOP_DB_NAME'),env('DEVELOP_DB_USERNAME'),env('DEVELOP_DB_PASSWORD'),intval(env('DEVELOP_DB_PORT')));
            static::$faker = ioc('faker');
            static::$table = ioc('table')->from(static::$from);
            static::$set = collect();

            $values = '';

            for ($i=0;$i<static::$generate;$i++)
            {
                static::each(static::$faker,static::$table,new static());

                $tmp = collect();

                foreach (static::$table->columns() as $column)
                    $tmp->set(static::$set->get($column));

                append($values, '('. trim($tmp->join(),', ') . '),');

                static::$set->clear();

                $tmp->clear();
            }

            $x = collect(static::$table->columns())->join();

            $table = static::$from;

            $sql = trim("INSERT INTO {$table} ($x) VALUES {$values}",', ') ;

            return static::$connexion->set($sql)->execute();

        }

    }
}