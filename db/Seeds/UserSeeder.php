<?php


namespace Base\Seeds {


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
                    case 'name':
                        $seeder->set($column,$generator->name());
                    break;
                    case 'email':
                        $seeder->set($column,$generator->email);
                    break;
                    case 'phone':
                        $seeder->set($column,$generator->phoneNumber);
                    break;
                    case 'created_at':
                        $seeder->set($column,now()->toDateTimeString());
                    break;
                    default:
                        $seeder->set($column,'NULL');
                    break;
                }
            }
        }
    }
}