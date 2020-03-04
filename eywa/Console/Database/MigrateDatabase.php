<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Migration\Migrate;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class MigrateDatabase extends Command
    {
        protected static $defaultName = 'db:migrate';

        protected function configure():void
        {
            $this->setDescription('Run the migrations')
                ->setHelp('php shaolin db:migrate env');
            ;
        }

        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            if (Migrate::check_migrate())
            {
                $io->warning('Nothing to migrate');
                return 0;
            }
            $listes = Migrate::list();

            $end = sum($listes);
            $i = 0;

            do{
                if(Migrate::run('up',$io) !== 0)
                {
                    $io->error('A migration has fail');
                    die();
                }

                $i++;
            }while($i!==$end);
            $io->success('All migration has been executed successfully');
            return 0;

        }
    }
}