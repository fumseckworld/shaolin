<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Base\Base;

    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class CleanDatabase extends Command
    {
        protected static $defaultName = 'db:clean';

        protected function configure():void
        {
            $this->setDescription("Truncate all tables")->addArgument('env', InputArgument::REQUIRED, 'The base environment');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $env = strval($input->getArgument('env'));

            not_in(['dev','prod','any'], $env, true, "Only dev, prod or any must be used");

            $io = new SymfonyStyle($input, $output);

            if ((new Base($env))->clean()) {
                $io->success('All tables are now empty');
                return 0;
            }

            $io->error('Failed to clean the database');
            return 1;
        }
    }
}
