<?php


namespace Eywa\Console\Database {

    use Eywa\Database\Migration\CreateMigrationTable;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class SetupDatabase extends Command
    {
        protected static $defaultName = 'db:setup';

        protected function configure()
        {
            $this->setDescription("Create all databases and users rights")->addArgument('env',InputArgument::REQUIRED,'The base environment');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $env = $input->getArgument('env');

            not_in(['dev','prod','any'],$env,true,"Only dev, prod or any must be used");

            $io = new SymfonyStyle($input,$output);

            if(development()->setup() && production()->setup()  &&(new CreateMigrationTable(production(),'up','prod'))->up() && (new CreateMigrationTable(development(),'up','dev'))->up())
            {
                $io->success('All base was successfully created');
                return 0;
            }

            $io->error('Failed to configure the application base');
            return 1;
        }


    }
}