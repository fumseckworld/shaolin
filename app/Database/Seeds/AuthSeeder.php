<?php


namespace App\Database\Seeds {

    use Eywa\Database\Seed\Seeder;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    class AuthSeeder extends Seeder
    {
        protected static int $generate = 100;

        protected static string $from = 'auth';

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
                        $seeder->set($column, $generator->name());
                    break;
                    case 'email':
                        $seeder->set($column, $generator->email);
                    break;
                    case 'password':
                        $seeder->set($column, secure_password('00000000'));
                    break;
                    default:
                        $seeder->set($column, 'NULL');
                    break;
                }
            }
        }
    }
}