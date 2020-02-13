<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Migration\Migrate;
    use Eywa\Database\Seed\Seeding;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class MigrateDatabase extends Command
    {
        protected static $defaultName = 'db:migrate';

        protected function configure()
        {

            $base = env('DB_NAME','eywa');
            $this->setDescription('Run the migrations')
                ->setHelp('php shaolin db:migrate env')->addArgument('environment', InputArgument::REQUIRED, 'The base to select');
            ;
        }

        public function execute(InputInterface $input,  OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);





            return 0;
        }
    }
}