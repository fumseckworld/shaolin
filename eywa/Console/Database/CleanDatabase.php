<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Base\Base;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class CleanDatabase extends Command
    {
        protected static $defaultName = 'db:clean';

        protected function configure()
        {
            $this->setDescription("Truncate all tables");
        }

        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);
            if((new Base())->clean())
            {
                $io->success('All tables are now empty');
                return 0;
            }

            $io->error('Failed to clean the database');
            return 1;
        }


    }
}