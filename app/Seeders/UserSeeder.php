<?php


namespace App\Seeders {


    use Eywa\Database\Seed\Seed;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    class UserSeeder extends Seed
    {

        protected static int $generate = 100;

        protected static string $from = 'users';

        /**
         * @inheritDoc
         */
        public function each(Generator $generator, Table $table, Seed $seed): Seed
        {
            foreach ($table->columns() as $column)
            {
                switch ($column)
                {
                    case 'created_at':
                        $seed->set($column,now()->toDateTimeString());
                    break;
                    case different($column,$table->primary()):
                        $seed->set($column,$generator->word());
                    break;
                    default:
                        $seed->set($column,'NULL');
                    break;
                }
            }
            return $seed;
        }
    }
}