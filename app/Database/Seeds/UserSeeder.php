<?php


namespace App\Database\Seeds {


    use Eywa\Database\Seed\Seeder;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    class UserSeeder extends Seeder
    {

        protected static int $generate = 100;

        protected static string $from = 'users';

        /**
         * @inheritDoc
         */
        public function each(Generator $generator, Table $table, Seeder $seeder): void
        {
            foreach ($table->columns() as $column)
            {
                switch ($column)
                {
                    case 'created_at':
                        $seeder->set($column,now()->toDateTimeString());
                    break;
                    case different($column,$table->primary()):
                        $seeder->set($column,$generator->word());
                    break;
                    default:
                        $seeder->set($column,'NULL');
                    break;
                }
            }
        }
    }
}