<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Migration\Migrate;
    use Eywa\Time\Timing;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class RollbackDatabase extends Command
    {
        protected static $defaultName = 'db:rollback';

        protected function configure():void
        {
            $this->setDescription('Rollback the migrations')
                ->setHelp('php shaolin db:migrate env');
            ;
        }

        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            if (Migrate::check_rollback())
            {
                $io->warning('Nothing to rollback');
                return 0;
            }
            return Migrate::run('down',$io);

        }
    }
}