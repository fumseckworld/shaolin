<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateSeeds extends Command
    {

        protected static $defaultName = 'make:seed';

        protected function configure():void
        {

            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new migration')
                ->addArgument('table',InputArgument::REQUIRED,'The table name')
                ->addArgument('seed', InputArgument::REQUIRED, 'The migration name.');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            $seed = strval($input->getArgument('seed'));

            if(preg_match("#^[a-z]([a-z_]+)$#",$seed) !== 1)
            {
                $io->error('You must use snake case syntax to generate the seeder');
                return  1;
            }

            $class = collect(explode('_',$seed))->for('ucfirst')->join('');

            $file  =  base('db','Seeds',"$class.php");

            if (file_exists($file))
            {
                $io->error(sprintf('The %s seeder already exist',$class));
                return 1;
            }

            $io->title('Generation of the seeder');

            $table  = strval($input->getArgument('table'));


            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php


namespace Base\Seeds {


    use Eywa\Database\Seed\Seeder;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    class $class extends Seeder
    {

        public static int \$generate = 100;

        public static string \$from = '$table';

        public static string \$title = 'Starting the %s seeding';

        public static string \$success_message = 'We have added %d records in the %s table';

        public static string \$error_message = 'Sorry the seed has fail';

        /**
         * @inheritDoc
         */
        public function each(Generator \$generator, Table \$table, Seeder \$seeder): void
        {
            foreach (\$table->columns() as \$column)
            {
                switch (\$column)
                {
                    default:
                       \$seeder->primary(\$column);
                    break;
                }
            }
        }
    }
}")->flush())
            {
                $io->success(sprintf('The %s seeder was successfully generated',$class));
                return 0;
            }

            $io->error(sprintf('The %s seeder creation has failed',$class));
            return 1;


        }
    }
}