<?php


namespace Base\Seeds {


    use Eywa\Database\Seed\Seeder;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    class UserSeeder extends Seeder
    {

        public static int $generate = 100;

        public static string $from = 'users';

        public static string $title = 'Starting the %s seeding';

        public static string $success_message = 'We have added %d records in the %s table';

        public static string $error_message = 'Sorry the seed has fail';

        /**
         * @inheritDoc
         */
        public function each(Generator $generator, Table $table, Seeder $seeder): void
        {


            foreach ($table->columns() as $column)
            {
                switch ($column)
                {
                    case 'username':
                        $seeder->set($column,$generator->name());
                    break;
                    case 'email':
                        $seeder->set($column,$generator->email);
                    break;
                    case 'phone':
                        $seeder->set($column,$generator->phoneNumber);
                    break;
                    default:
                       $seeder->primary($column);
                    break;

                }
            }
        }
    }
}