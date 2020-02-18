<?php


namespace Eywa\Console\Database {


    use Eywa\Time\Timing;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class CreateMigrationTable extends Command
    {
        protected static $defaultName = 'db:configure';

        protected function configure()
        {
            $this->setDescription('Create the migrations table');
        }

        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);
            if((new \Eywa\Database\Migration\CreateMigrationTable(production()))->up())
            {
                $io->success('The production migration table has been created successfully');

            }else{
                $io->error('The migrations table generation has failed');
                return 1;
            }
            if((new \Eywa\Database\Migration\CreateMigrationTable(development()))->up())
            {
                $io->success('The development migration table has been created successfully');

            }else{
                $io->error('The migrations table generation has failed');
                return 1;

            }
            return 0;
        }
    }
}