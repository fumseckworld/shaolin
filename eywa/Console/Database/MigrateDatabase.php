<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Migration\Migrate;
    use Eywa\Time\Timing;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class MigrateDatabase extends Command
    {
        protected static $defaultName = 'db:migrate';

        protected function configure()
        {
            $this->setDescription('Run the migrations')
                ->setHelp('php shaolin db:migrate env');
            ;
        }

        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);
            $time = (new Timing());
            if (Migrate::run('up'))
            {
                $x = $time->check();
                $io->success("The migration has been executed successfully : $x ms");
                return 0;
            }

            $io->error('The migration task has failed');
            return 1;
        }
    }
}