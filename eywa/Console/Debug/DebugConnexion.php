<?php

namespace Eywa\Console\Debug {

    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class DebugConnexion extends Command
    {
        protected static $defaultName = 'check:connexion';


        public function configure(): void
        {
            $this->setDescription('Check all connexion to all bases');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);


            if (production()->connected()) {
                $io->success('The production database is ready to use');
            } else {
                $io->error('The connexion to the production database has failed');
                return 1;
            }
            if (development()->connected()) {
                $io->success('The development database is ready to use');
            } else {
                $io->error('The connexion to the development database has failed');
                return 1;
            }
            if (tests()->connected()) {
                $io->success('The tests database is ready to use');
            } else {
                $io->error('The connexion to the test database has failed');
                return 1;
            }

            return 0;
        }
    }
}
