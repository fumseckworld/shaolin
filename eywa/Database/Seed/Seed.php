<?php


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


    class Seed
    {
        /**
         *
         * The table name
         *
         */
        private string $from = '';

        /**
         *
         * The number of records
         *
         */
        private int $limit = 100;

        /**
         *
         * The data generator
         *
         */
        private Generator $faker;


        /**
         *
         * An instance of application table
         *
         */
        private Table $table;

        /**
         *
         * The seeding values
         *
         */

        private string $values = '';

        /**
         *
         * The connexion to the base
         *
         */
        private Connexion $connexion;

        /**
         *
         * The set columns
         *
         */
        private Collect $set;


        /**
         * Seed constructor.
         * @param string $from
         * @param int $records
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Exception
         */
        public function __construct(string $from, int $records = 100)
        {
            $this->from = $from;
            $this->limit = $records;
            $this->connexion = ioc(Connect::class)->get();
            $this->faker = ioc('faker')->get();
            $this->table = ioc('table')->get();

            $this->set = collect();
            $this->table->from($from);
        }

        /**
         *
         * Call the seeding
         *
         * @param string $from
         * @param int $records
         *
         * @return Seed
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public static function from(string $from, int $records = 100): Seed
        {
            return  new static($from,$records);
        }

        /**
         *
         * Get the seeding limit
         *
         * @return int
         *
         */
        public function limit(): int
        {
           return $this->limit;
        }

        /**
         *
         * Build the seed value for a record
         *
         * @param callable $callback
         *
         * @return Seed
         *
         * @throws Kedavra
         *
         */
        public function each(callable $callback): Seed
        {
            for ($i=0;different($i,$this->limit());$i++)
            {

                call_user_func_array($callback,[$this->faker,$this->table,$this]);

                $tmp = collect();

                foreach ($this->table->columns() as $column)
                    $tmp->set($this->set->get($column));

                append($this->values, '('. trim($tmp->join(),', ') . '),');

                $this->set->clear();

                $tmp->clear();
            }

            return  $this;
        }

        /**
         *
         * Set a column value
         *
         * @param string $column
         * @param $value
         *
         * @return Seed
         *
         * @throws Kedavra
         *
         */
        public function set(string $column,$value): Seed
        {
            equal($column,$this->table->primary()) ? $this->set->put($column,$value) :   $this->set->put($column,$this->connexion->secure($value));

            return $this;
        }

        /**
         *
         * Execute the seeding
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function seed() : bool
        {
            $x = collect($this->table->columns())->join();

            $sql = trim("INSERT INTO {$this->from} ($x) VALUES {$this->values}",', ') ;

            return $this->connexion->set($sql)->execute();

        }

    }
}